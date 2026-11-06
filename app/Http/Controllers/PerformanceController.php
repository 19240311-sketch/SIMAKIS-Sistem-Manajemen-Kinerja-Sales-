<?php

namespace App\Http\Controllers;

use App\Models\PerformanceCriterion;
use App\Models\PerformanceEvaluation;
use App\Models\SalesTarget;
use App\Models\SalesTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PerformanceController extends Controller
{
    public function index(Request $request): View
    {
        $currentUser = Auth::user();

        $bulan = (int) ($request->input('bulan', date('n')));
        $tahun = (int) ($request->input('tahun', date('Y')));
        $selectedSalesId = $request->input('sales_id');

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $criteria = PerformanceCriterion::orderBy('urutan')->get();

        // Query sales list
        $salesQuery = User::where('peran', 'sales');
        if (!$currentUser->isSupervisor()) {
            $salesQuery->where('id', $currentUser->id);
        } elseif (!empty($selectedSalesId)) {
            $salesQuery->where('id', $selectedSalesId);
        }
        $salesList = $salesQuery->where('status_aktif', true)->orderBy('nama')->get();

        // Calculate rankings and performance for all active sales in that period
        $allActiveSales = User::where('peran', 'sales')->where('status_aktif', true)->get();

        $performanceData = $allActiveSales->map(function ($s) use ($startDate, $endDate, $tahun, $bulan) {
            $realisasiUnit = (int) SalesTransaction::valid()
                ->where('user_id', $s->id)
                ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
                ->sum('jumlah_unit');

            $targetObj = SalesTarget::where('user_id', $s->id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->first();

            $targetUnit = $targetObj ? $targetObj->target_unit : 0;
            $persentase = $targetUnit > 0 ? round(($realisasiUnit / $targetUnit) * 100, 1) : ($realisasiUnit > 0 ? 100.0 : 0.0);
            $statusKinerja = PerformanceCriterion::determineCategory($persentase);

            // Check existing evaluation record in DB if any
            $evaluation = PerformanceEvaluation::where('user_id', $s->id)
                ->where('periode_tahun', $tahun)
                ->where('periode_bulan', $bulan)
                ->first();

            return [
                'user' => $s,
                'target_unit' => $targetUnit,
                'realisasi_unit' => $realisasiUnit,
                'persentase' => $persentase,
                'status_kinerja' => $statusKinerja,
                'evaluation' => $evaluation,
            ];
        });

        // Compute rankings
        $ranked = $performanceData->sortByDesc('realisasi_unit')->values();
        $rankMap = [];
        foreach ($ranked as $idx => $item) {
            if ($idx > 0 && $item['realisasi_unit'] === $ranked[$idx - 1]['realisasi_unit']) {
                $rankMap[$item['user']->id] = $rankMap[$ranked[$idx - 1]['user']->id];
            } else {
                $rankMap[$item['user']->id] = $idx + 1;
            }
        }

        // Filter display list based on current user / selected sales
        $displayList = $ranked->filter(function ($item) use ($currentUser, $selectedSalesId) {
            if (!$currentUser->isSupervisor()) {
                return $item['user']->id === $currentUser->id;
            }
            if (!empty($selectedSalesId)) {
                return $item['user']->id == $selectedSalesId;
            }
            return true;
        })->map(function ($item) use ($rankMap) {
            $item['peringkat'] = $rankMap[$item['user']->id] ?? 1;
            return $item;
        });

        // History of evaluations
        $historyQuery = PerformanceEvaluation::with(['user', 'supervisor'])
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc');

        if (!$currentUser->isSupervisor()) {
            $historyQuery->where('user_id', $currentUser->id);
        } elseif (!empty($selectedSalesId)) {
            $historyQuery->where('user_id', $selectedSalesId);
        }

        $history = $historyQuery->paginate(10)->withQueryString();

        $allSalesOptions = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        return view('performance.index', compact(
            'displayList',
            'criteria',
            'bulan',
            'tahun',
            'selectedSalesId',
            'allSalesOptions',
            'history'
        ));
    }

    public function storeEvaluation(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat menyimpan catatan evaluasi kinerja.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'periode_bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'periode_tahun' => ['required', 'integer', 'min:2020', 'max:2035'],
            'target_unit' => ['required', 'integer', 'min:0'],
            'realisasi_unit' => ['required', 'integer', 'min:0'],
            'persentase_capaian' => ['required', 'numeric', 'min:0'],
            'peringkat' => ['required', 'integer', 'min:1'],
            'status_kinerja' => ['required', 'string'],
            'catatan_evaluasi' => ['required', 'string'],
            'rekomendasi' => ['nullable', 'string'],
            'rencana_tindak_lanjut' => ['nullable', 'string'],
        ]);

        PerformanceEvaluation::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'periode_tahun' => $validated['periode_tahun'],
                'periode_bulan' => $validated['periode_bulan'],
            ],
            [
                'supervisor_id' => $currentUser->id,
                'target_unit' => $validated['target_unit'],
                'realisasi_unit' => $validated['realisasi_unit'],
                'persentase_capaian' => $validated['persentase_capaian'],
                'peringkat' => $validated['peringkat'],
                'status_kinerja' => $validated['status_kinerja'],
                'catatan_evaluasi' => $validated['catatan_evaluasi'],
                'rekomendasi' => $validated['rekomendasi'] ?? null,
                'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'] ?? null,
            ]
        );

        return back()->with('success', 'Catatan evaluasi kinerja berhasil disimpan!');
    }

    public function updateCriteria(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat mengatur kriteria penilaian.');
        }

        $validated = $request->validate([
            'criteria' => ['required', 'array'],
            'criteria.*.id' => ['required', 'exists:performance_criteria,id'],
            'criteria.*.persentase_min' => ['required', 'numeric', 'min:0'],
            'criteria.*.persentase_max' => ['nullable', 'numeric', 'min:0'],
            'criteria.*.deskripsi' => ['nullable', 'string'],
        ]);

        foreach ($validated['criteria'] as $row) {
            PerformanceCriterion::where('id', $row['id'])->update([
                'persentase_min' => $row['persentase_min'],
                'persentase_max' => $row['persentase_max'] !== null && $row['persentase_max'] !== '' ? $row['persentase_max'] : null,
                'deskripsi' => $row['deskripsi'] ?? null,
            ]);
        }

        return back()->with('success', 'Kriteria batas persentase penilaian kinerja berhasil diperbarui!');
    }
}
