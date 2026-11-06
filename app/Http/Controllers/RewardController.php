<?php

namespace App\Http\Controllers;

use App\Models\RewardProgram;
use App\Models\SalesReward;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function index(Request $request): View
    {
        $currentUser = Auth::user();

        $bulan = (int) ($request->input('bulan', date('n')));
        $tahun = (int) ($request->input('tahun', date('Y')));

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Get active reward programs for the selected period or general active programs
        $programs = RewardProgram::where('is_active', true)
            ->where(function ($q) use ($bulan, $tahun) {
                $q->where(function ($sub) use ($bulan, $tahun) {
                    $sub->where('periode_bulan', $bulan)
                        ->where('periode_tahun', $tahun);
                })->orWhere(function ($sub) {
                    $sub->whereNull('periode_bulan')->whereNull('periode_tahun');
                });
            })
            ->get();

        // If no program exists for this period, check default program or create standard 15-unit program
        if ($programs->isEmpty()) {
            $defaultProgram = RewardProgram::firstOrCreate(
                ['nama_program' => 'Reward Penjualan 15 Unit Bulanan'],
                [
                    'deskripsi' => 'Program apresiasi sales yang berhasil menjual minimal 15 unit sepeda motor dalam satu bulan berjalan.',
                    'target_minimal_unit' => 15,
                    'jenis_hadiah' => 'bonus_insentif',
                    'nominal_hadiah' => 1500000,
                    'ketentuan' => 'Hanya berlaku untuk transaksi unit valid dan selesai verifikasi.',
                    'is_active' => true,
                    'dibuat_oleh' => $currentUser->id,
                ]
            );
            $programs = collect([$defaultProgram]);
        }

        $activeProgram = $programs->first();

        // Sales list for evaluation
        $salesQuery = User::where('peran', 'sales')->where('status_aktif', true);
        if (!$currentUser->isSupervisor()) {
            $salesQuery->where('id', $currentUser->id);
        }
        $salesList = $salesQuery->orderBy('nama')->get();

        // Calculate progress for each sales against the active reward program
        $salesProgress = $salesList->map(function ($s) use ($activeProgram, $startDate, $endDate, $tahun, $bulan) {
            $unitTerjual = (int) SalesTransaction::valid()
                ->where('user_id', $s->id)
                ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
                ->sum('jumlah_unit');

            $targetReward = $activeProgram->target_minimal_unit;
            $persentase = $targetReward > 0 ? round(($unitTerjual / $targetReward) * 100, 1) : 0;
            $sisaUnit = max(0, $targetReward - $unitTerjual);

            // Fetch or create sales reward record
            $rewardRecord = SalesReward::firstOrCreate(
                [
                    'reward_program_id' => $activeProgram->id,
                    'user_id' => $s->id,
                    'periode_bulan' => $bulan,
                    'periode_tahun' => $tahun,
                ],
                [
                    'realisasi_unit' => $unitTerjual,
                    'status' => $unitTerjual >= $targetReward ? 'menunggu_verifikasi' : 'belum_tercapai',
                ]
            );

            // Update realisasi_unit in case new transactions occurred
            if ($rewardRecord->realisasi_unit !== $unitTerjual) {
                $status = $rewardRecord->status;
                if ($status === 'belum_tercapai' && $unitTerjual >= $targetReward) {
                    $status = 'menunggu_verifikasi';
                }
                $rewardRecord->update([
                    'realisasi_unit' => $unitTerjual,
                    'status' => $status,
                ]);
            }

            return [
                'user' => $s,
                'program' => $activeProgram,
                'target_reward' => $targetReward,
                'realisasi_unit' => $unitTerjual,
                'sisa_unit' => $sisaUnit,
                'persentase' => $persentase,
                'is_eligible' => $unitTerjual >= $targetReward,
                'reward_record' => $rewardRecord,
            ];
        });

        // Reward history
        $historyQuery = SalesReward::with(['user', 'program', 'verifier'])
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc');

        if (!$currentUser->isSupervisor()) {
            $historyQuery->where('user_id', $currentUser->id);
        }

        $history = $historyQuery->paginate(10)->withQueryString();

        return view('rewards.index', compact(
            'programs',
            'activeProgram',
            'salesProgress',
            'bulan',
            'tahun',
            'history'
        ));
    }

    public function createProgram(): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat membuat program reward.');
        }

        return view('rewards.create-program');
    }

    public function storeProgram(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat membuat program reward.');
        }

        $validated = $request->validate([
            'nama_program' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'target_minimal_unit' => ['required', 'integer', 'min:1', 'max:500'],
            'periode_bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'periode_tahun' => ['nullable', 'integer', 'min:2020', 'max:2035'],
            'jenis_hadiah' => ['required', 'in:bonus_insentif,voucher_belanja,merchandise,penghargaan_sales_terbaik'],
            'nominal_hadiah' => ['required', 'numeric', 'min:0'],
            'ketentuan' => ['nullable', 'string'],
        ]);

        $prog = RewardProgram::create([
            'nama_program' => $validated['nama_program'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'target_minimal_unit' => $validated['target_minimal_unit'],
            'periode_bulan' => $validated['periode_bulan'] ?? null,
            'periode_tahun' => $validated['periode_tahun'] ?? null,
            'jenis_hadiah' => $validated['jenis_hadiah'],
            'nominal_hadiah' => $validated['nominal_hadiah'],
            'ketentuan' => $validated['ketentuan'] ?? null,
            'is_active' => true,
            'dibuat_oleh' => $currentUser->id,
        ]);

        ActivityLogService::log(
            activityType: 'tambah',
            module: 'reward',
            description: "Membuat program reward baru: {$prog->nama_program} (Target {$prog->target_minimal_unit} unit).",
            record: $prog
        );

        return redirect()->route('rewards.index')
            ->with('success', 'Program reward baru berhasil ditambahkan!');
    }

    public function verify(Request $request, SalesReward $reward): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang berhak memverifikasi pencapaian reward.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:menunggu_verifikasi,disetujui,hadiah_diberikan,belum_tercapai'],
            'catatan' => ['nullable', 'string'],
        ]);

        $updateData = [
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $reward->catatan,
            'diverifikasi_oleh' => $currentUser->id,
            'tanggal_verifikasi' => now(),
        ];

        if ($validated['status'] === 'hadiah_diberikan') {
            $updateData['tanggal_pemberian'] = now();
        }

        $reward->update($updateData);

        ActivityLogService::log(
            activityType: 'status_change',
            module: 'reward',
            description: "Verifikasi pencapaian reward sales {$reward->sales?->nama}: status diubah menjadi '{$reward->status}'.",
            record: $reward,
            afterData: ['status' => $reward->status, 'sales' => $reward->sales?->nama]
        );

        return back()->with('success', 'Status reward sales berhasil diperbarui menjadi: ' . $reward->status_label);
    }
}
