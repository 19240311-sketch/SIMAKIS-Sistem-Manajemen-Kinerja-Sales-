<?php

namespace App\Http\Controllers;

use App\Models\PerformanceCriterion;
use App\Models\SalesTarget;
use App\Models\SalesTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SalesDataController extends Controller
{
    public function index(Request $request): View
    {
        $currentUser = Auth::user();

        $bulan = (int) ($request->input('bulan', date('n')));
        $tahun = (int) ($request->input('tahun', date('Y')));
        $statusAktif = $request->input('status_aktif');
        $search = $request->input('q');
        $sortOrder = $request->input('sort', 'desc'); // desc or asc by sales

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $salesQuery = User::where('peran', 'sales');

        if ($statusAktif !== null && $statusAktif !== '') {
            $salesQuery->where('status_aktif', (bool) $statusAktif);
        }

        if (!empty($search)) {
            $salesQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $allSales = $salesQuery->get();

        // Calculate metrics for each sales in the chosen period
        $salesData = $allSales->map(function ($s) use ($startDate, $endDate, $tahun, $bulan) {
            // Unit terjual dari transaksi valid
            $unitTerjual = (int) SalesTransaction::valid()
                ->where('user_id', $s->id)
                ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
                ->sum('jumlah_unit');

            // Target bulanan jika ada
            $targetRecord = SalesTarget::where('user_id', $s->id)
                ->where(function ($tq) use ($tahun, $bulan, $startDate, $endDate) {
                    $tq->where(function ($m) use ($tahun, $bulan) {
                        $m->where('periode_tipe', 'bulanan')
                          ->where('tahun', $tahun)
                          ->where('bulan', $bulan);
                    })->orWhere(function ($d) use ($startDate, $endDate) {
                        $d->where('tanggal_mulai', '<=', $endDate->toDateString())
                          ->where('tanggal_selesai', '>=', $startDate->toDateString());
                    });
                })->first();

            $targetUnit = $targetRecord ? $targetRecord->target_unit : 0;
            $persentase = $targetUnit > 0 ? round(($unitTerjual / $targetUnit) * 100, 1) : ($unitTerjual > 0 ? 100.0 : 0.0);
            $statusKinerja = PerformanceCriterion::determineCategory($persentase);
            $badgeColor = PerformanceCriterion::getBadgeColor($statusKinerja);

            return [
                'user' => $s,
                'target_unit' => $targetUnit,
                'unit_terjual' => $unitTerjual,
                'persentase' => $persentase,
                'status_kinerja' => $statusKinerja,
                'badge_color' => $badgeColor,
            ];
        });

        // Sort by unit_terjual
        if ($sortOrder === 'asc') {
            $sorted = $salesData->sortBy('unit_terjual')->values();
        } else {
            $sorted = $salesData->sortByDesc('unit_terjual')->values();
        }

        // Calculate standard ranking based on unit_terjual descending
        $rankedList = $salesData->sortByDesc('unit_terjual')->values();
        $rankMap = [];
        $currentRank = 1;
        foreach ($rankedList as $index => $item) {
            if ($index > 0 && $item['unit_terjual'] === $rankedList[$index - 1]['unit_terjual']) {
                $rankMap[$item['user']->id] = $rankMap[$rankedList[$index - 1]['user']->id];
            } else {
                $rankMap[$item['user']->id] = $index + 1;
            }
        }

        // Attach ranks to sorted results
        $finalSales = $sorted->map(function ($item) use ($rankMap) {
            $item['peringkat'] = $rankMap[$item['user']->id] ?? 1;
            return $item;
        });

        return view('sales.index', compact(
            'finalSales',
            'bulan',
            'tahun',
            'statusAktif',
            'search',
            'sortOrder'
        ));
    }

    public function create(): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat menambah akun sales.');
        }

        return view('sales.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat mendaftarkan sales baru.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'cabang' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'cabang' => $validated['cabang'] ?? 'Yamaha JG Purwakarta',
            'password' => Hash::make($validated['password']),
            'peran' => 'sales',
            'status_aktif' => true,
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Data Sales ' . $validated['nama'] . ' berhasil ditambahkan!');
    }

    public function show(User $sale): View
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor() && $currentUser->id !== $sale->id) {
            abort(403, 'Akses terbatas untuk akun sales lain.');
        }

        $bulan = (int) request('bulan', date('n'));
        $tahun = (int) request('tahun', date('Y'));

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Riwayat Transaksi Penjualan
        $transactions = SalesTransaction::with(['motorcycle', 'color'])
            ->where('user_id', $sale->id)
            ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalUnitTerjual = $transactions->where('status_transaksi', 'valid')->sum('jumlah_unit');

        // Target
        $target = SalesTarget::where('user_id', $sale->id)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        $targetUnit = $target ? $target->target_unit : 0;
        $persentase = $targetUnit > 0 ? round(($totalUnitTerjual / $targetUnit) * 100, 1) : 0;
        $statusKinerja = PerformanceCriterion::determineCategory($persentase);

        // Temuan Lapangan
        $findings = $sale->fieldFindings()
            ->with('motorcycle')
            ->orderBy('tanggal_temuan', 'desc')
            ->take(5)
            ->get();

        // Kehadiran Rapat
        $attendances = $sale->meetingAttendances()
            ->with('meeting')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('sales.show', compact(
            'sale',
            'bulan',
            'tahun',
            'transactions',
            'totalUnitTerjual',
            'target',
            'targetUnit',
            'persentase',
            'statusKinerja',
            'findings',
            'attendances'
        ));
    }

    public function edit(User $sale): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat mengubah data sales.');
        }

        return view('sales.edit', compact('sale'));
    }

    public function update(Request $request, User $sale): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat mengubah data sales.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $sale->id],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'cabang' => ['nullable', 'string', 'max:100'],
            'status_aktif' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $updateData = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'cabang' => $validated['cabang'] ?? 'Yamaha JG Purwakarta',
            'status_aktif' => (bool) $validated['status_aktif'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $sale->update($updateData);

        return redirect()->route('sales.index')
            ->with('success', 'Data Sales ' . $sale->nama . ' berhasil diperbarui!');
    }

    public function toggleStatus(User $sale): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat mengaktifkan atau menonaktifkan akun sales.');
        }

        $sale->update(['status_aktif' => !$sale->status_aktif]);
        $statusText = $sale->status_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun sales {$sale->nama} berhasil {$statusText}.");
    }
}
