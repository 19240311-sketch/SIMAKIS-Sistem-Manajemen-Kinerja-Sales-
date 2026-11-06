<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FieldFinding;
use App\Models\Meeting;
use App\Models\MeetingAttendance;
use App\Models\Motorcycle;
use App\Models\PerformanceCriterion;
use App\Models\PerformanceEvaluation;
use App\Models\Prospect;
use App\Models\ProspectActivity;
use App\Models\ProspectStatus;
use App\Models\RewardProgram;
use App\Models\SalesReward;
use App\Models\SalesTarget;
use App\Models\SalesTransaction;
use App\Models\Source;
use App\Models\SupervisorHandover;
use App\Models\SupervisorHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $currentUser = Auth::user();
        $today = Carbon::today();

        // 1. Filter Mode Data (Semua / Hanya Data Real Operasional)
        $dataMode = $request->input('data_mode', 'all'); // 'all' (termasuk demo) atau 'real' (hanya operasional)
        $includeDemo = ($dataMode !== 'real');

        // 2. Filter Periode Evaluasi Reguler
        $filterType = $request->input('period_type', 'month');
        $bulan = (int) ($request->input('bulan', $today->month));
        $tahun = (int) ($request->input('tahun', $today->year));

        switch ($filterType) {
            case 'today':
                $startDate = $today->copy()->startOfDay();
                $endDate = $today->copy()->endOfDay();
                $periodeLabel = 'Hari Ini (' . $today->locale('id')->isoFormat('D MMMM Y') . ')';
                break;
            case 'week':
                $startDate = $today->copy()->startOfWeek();
                $endDate = $today->copy()->endOfWeek();
                $periodeLabel = 'Minggu Ini (' . $startDate->format('d M') . ' - ' . $endDate->locale('id')->isoFormat('D MMMM Y') . ')';
                break;
            case '3_months':
                $refDate = Carbon::createFromDate($tahun, $bulan, 1);
                $startDate = $refDate->copy()->subMonths(2)->startOfMonth();
                $endDate = $refDate->copy()->endOfMonth();
                $periodeLabel = '3 Bulan Terakhir (' . $startDate->locale('id')->isoFormat('MMMM Y') . ' - ' . $endDate->locale('id')->isoFormat('MMMM Y') . ')';
                break;
            case '6_months':
                $refDate = Carbon::createFromDate($tahun, $bulan, 1);
                $startDate = $refDate->copy()->subMonths(5)->startOfMonth();
                $endDate = $refDate->copy()->endOfMonth();
                $periodeLabel = '6 Bulan Terakhir (' . $startDate->locale('id')->isoFormat('MMMM Y') . ' - ' . $endDate->locale('id')->isoFormat('MMMM Y') . ')';
                break;
            case '1_year':
            case 'year':
                $startDate = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($tahun, 12, 31)->endOfYear();
                $periodeLabel = 'Tahun ' . $tahun;
                break;
            case 'custom':
                $startDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : $today->copy()->startOfMonth();
                $endDate = $request->filled('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : $today->copy()->endOfMonth();
                $periodeLabel = $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
                break;
            case '1_month':
            case 'month':
            default:
                $filterType = 'month';
                $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
                $periodeLabel = 'Bulan ' . $startDate->locale('id')->isoFormat('MMMM Y');
                break;
        }

        // Program reward aktif
        $rewardProgram = RewardProgram::where('is_active', true);
        if (!$includeDemo) {
            $rewardProgram->where('is_demo', false);
        }
        $activeRewardProgram = $rewardProgram->first();
        $targetRewardUnit = $activeRewardProgram ? $activeRewardProgram->target_minimal_unit : 15;

        $statusDealId = ProspectStatus::where('kode', 'deal')->value('id');
        $statusBatalId = ProspectStatus::where('kode', 'batal')->value('id');

        // ==========================================
        // 0. DASHBOARD SUPER ADMIN
        // ==========================================
        if ($currentUser->isSuperAdmin()) {
            $totalUsers = User::count();
            $activeUsers = User::where('status_aktif', true)->count();
            $inactiveUsers = User::where('status_aktif', false)->count();
            $totalSales = User::where('peran', User::ROLE_SALES)->count();
            $totalSupervisor = User::where('peran', User::ROLE_SUPERVISOR)->count();
            $totalSuperAdmin = User::where('peran', User::ROLE_SUPER_ADMIN)->count();

            $activeSupervisor = User::where('peran', User::ROLE_SUPERVISOR)->where('status_aktif', true)->first();
            $activeSupervisorHistory = $activeSupervisor ? SupervisorHistory::where('user_id', $activeSupervisor->id)->where('status_jabatan', 'aktif')->first() : null;

            $totalHandovers = SupervisorHandover::count();
            $pendingHandovers = SupervisorHandover::whereIn('status', ['menunggu_serah_terima', 'dalam_proses'])->count();
            $completedHandovers = SupervisorHandover::where('status', 'selesai')->count();
            $recentHandovers = SupervisorHandover::with(['supervisorLama', 'supervisorBaru'])->latest('tanggal_serah_terima')->take(4)->get();

            $totalLogsCount = ActivityLog::count();
            $todayLogsCount = ActivityLog::whereDate('created_at', Carbon::today())->count();
            $recentLogs = ActivityLog::latest('created_at')->take(8)->get();

            return view('dashboard.super_admin', compact(
                'totalUsers',
                'activeUsers',
                'inactiveUsers',
                'totalSales',
                'totalSupervisor',
                'totalSuperAdmin',
                'activeSupervisor',
                'activeSupervisorHistory',
                'totalHandovers',
                'pendingHandovers',
                'completedHandovers',
                'recentHandovers',
                'totalLogsCount',
                'todayLogsCount',
                'recentLogs',
                'periodeLabel',
                'dataMode'
            ));
        }

        // ==========================================
        // PERHITUNGAN PRESTASI SALES (SUPERVISOR & SALES)
        // Digunakan secara seragam untuk memastikan konsistensi 100%
        // ==========================================
        // Filter user sales yang aktif
        $salesQuery = User::where('peran', 'sales')->where('status_aktif', true);
        if (!$includeDemo) {
            $salesQuery->where('is_demo', false);
        }
        $activeSales = $salesQuery->orderBy('nama')->get();
        $totalSalesAktif = $activeSales->count();

        // Query transaksi valid periode ini
        $trxQuery = SalesTransaction::valid()
            ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()]);
        if (!$includeDemo) {
            $trxQuery->where('is_demo', false);
        }
        $totalUnitTerjual = (int) $trxQuery->sum('jumlah_unit');
        $totalPendapatan = (int) (clone $trxQuery)->sum('total_harga');

        // Query target keseluruhan periode ini
        $targetQuery = SalesTarget::where('tanggal_mulai', '<=', $endDate->toDateString())
            ->where('tanggal_selesai', '>=', $startDate->toDateString());
        if (!$includeDemo) {
            $targetQuery->where('is_demo', false);
        }
        $totalTargetPenjualan = (int) $targetQuery->sum('target_unit');
        $persentaseCapaian = $totalTargetPenjualan > 0 
            ? round(($totalUnitTerjual / $totalTargetPenjualan) * 100, 1) 
            : 0;

        // 1. LEADERBOARD BULANAN DENGAN ATURAN PEMERATAAN & TIE-BREAKER
        $salesMetrics = $activeSales->map(function ($s) use ($startDate, $endDate, $includeDemo, $targetRewardUnit, $statusDealId) {
            $uQuery = SalesTransaction::valid()
                ->where('user_id', $s->id)
                ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()]);
            if (!$includeDemo) {
                $uQuery->where('is_demo', false);
            }
            $units = (int) $uQuery->sum('jumlah_unit');
            $omset = (int) (clone $uQuery)->sum('total_harga');

            $tQuery = SalesTarget::where('user_id', $s->id)
                ->where('tanggal_mulai', '<=', $endDate->toDateString())
                ->where('tanggal_selesai', '>=', $startDate->toDateString());
            if (!$includeDemo) {
                $tQuery->where('is_demo', false);
            }
            $target = (int) $tQuery->sum('target_unit');
            if ($target === 0) {
                $target = $targetRewardUnit; // Default target bulanan dealer
            }

            $pct = $target > 0 ? round(($units / $target) * 100, 1) : 0;
            $selisih = $target - $units;
            $statusKinerja = PerformanceCriterion::determineCategory($pct);

            $pQ = Prospect::where('user_id', $s->id)->whereBetween('created_at', [$startDate, $endDate]);
            if (!$includeDemo) {
                $pQ->where('is_demo', false);
            }
            $totalProspects = (clone $pQ)->count();
            $dealProspects = (clone $pQ)->where(function ($q) use ($statusDealId) {
                $q->where('status_prospek_id', $statusDealId)
                  ->orWhereHas('salesTransactions', fn($st) => $st->where('status_transaksi', 'valid'));
            })->count();

            $conversionRate = $totalProspects > 0 ? round(($dealProspects / $totalProspects) * 100, 1) : 0;

            return [
                'user' => $s,
                'units' => $units,
                'target' => $target,
                'percentage' => $pct,
                'selisih' => $selisih,
                'omset' => $omset,
                'total_prospects' => $totalProspects,
                'deal_prospects' => $dealProspects,
                'conversion_rate' => $conversionRate,
                'status_kinerja' => $statusKinerja,
                'reached_target' => $units >= $target,
                'reached_reward' => $units >= $targetRewardUnit,
                'almost_target' => ($pct >= 75 && $pct < 100),
            ];
        });

        // Urutkan pemenang bulanan
        $rankedSales = $salesMetrics->sort(function ($a, $b) {
            if ($a['units'] !== $b['units']) {
                return $b['units'] <=> $a['units'];
            }
            if ($a['omset'] !== $b['omset']) {
                return $b['omset'] <=> $a['omset'];
            }
            return $b['conversion_rate'] <=> $a['conversion_rate'];
        })->values();

        $leaderboard = $rankedSales->map(function ($item, $idx) use ($rankedSales) {
            if ($idx > 0 && $item['units'] === $rankedSales[$idx - 1]['units'] && $item['omset'] === $rankedSales[$idx - 1]['omset']) {
                $item['rank'] = $rankedSales[$idx - 1]['rank'] ?? ($idx + 1);
            } else {
                $item['rank'] = $idx + 1;
            }
            return $item;
        });

        // Sales Terbaik Bulan Ini
        $salesTerbaikBulanIni = null;
        $topCandidate = $leaderboard->first();
        if ($topCandidate && $topCandidate['units'] > 0) {
            $salesTerbaikBulanIni = [
                'user' => $topCandidate['user'],
                'units' => $topCandidate['units'],
                'target' => $topCandidate['target'],
                'percentage' => $topCandidate['percentage'],
                'omset' => $topCandidate['omset'],
                'deal_prospects' => $topCandidate['deal_prospects'],
                'total_prospects' => $topCandidate['total_prospects'],
                'conversion_rate' => $topCandidate['conversion_rate'],
                'periode_label' => Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM Y'),
                'reached_reward' => $topCandidate['reached_reward'],
                'is_winner' => true,
            ];
        }

        $countReachedTarget = $salesMetrics->where('reached_target', true)->count();
        $countReachedReward = $salesMetrics->where('reached_reward', true)->count();
        $countAlmostTarget = $salesMetrics->where('almost_target', true)->count();

        // 2. REKAP PRESTASI SALES SELAMA ENAM BULAN
        $recapEndMonth = (int) $request->input('recap_month', $bulan);
        $recapEndYear = (int) $request->input('recap_year', $tahun);
        $recapEndDate = Carbon::createFromDate($recapEndYear, $recapEndMonth, 1)->endOfMonth();
        $recapStartDate = Carbon::createFromDate($recapEndYear, $recapEndMonth, 1)->subMonths(5)->startOfMonth();
        $recapPeriodeLabel = $recapStartDate->locale('id')->isoFormat('MMMM Y') . ' – ' . $recapEndDate->locale('id')->isoFormat('MMMM Y');

        $sixMonthsList = [];
        for ($mo = 5; $mo >= 0; $mo--) {
            $mDate = $recapEndDate->copy()->subMonths($mo);
            $sixMonthsList[] = [
                'month' => $mDate->month,
                'year' => $mDate->year,
                'label' => $mDate->locale('id')->isoFormat('MMM Y'),
                'full_label' => $mDate->locale('id')->isoFormat('MMMM Y'),
                'start' => $mDate->copy()->startOfMonth(),
                'end' => $mDate->copy()->endOfMonth(),
            ];
        }

        $sixMonthMetrics = $activeSales->map(function ($s) use ($sixMonthsList, $recapStartDate, $recapEndDate, $includeDemo, $targetRewardUnit, $statusDealId) {
            $trx6mQuery = SalesTransaction::valid()
                ->where('user_id', $s->id)
                ->whereBetween('tanggal_transaksi', [$recapStartDate->toDateString(), $recapEndDate->toDateString()]);
            if (!$includeDemo) {
                $trx6mQuery->where('is_demo', false);
            }
            $totalUnits6m = (int) $trx6mQuery->sum('jumlah_unit');
            $totalOmset6m = (int) (clone $trx6mQuery)->sum('total_harga');

            $target6mQuery = SalesTarget::where('user_id', $s->id)
                ->where('tanggal_mulai', '<=', $recapEndDate->toDateString())
                ->where('tanggal_selesai', '>=', $recapStartDate->toDateString());
            if (!$includeDemo) {
                $target6mQuery->where('is_demo', false);
            }
            $totalTarget6m = (int) $target6mQuery->sum('target_unit');
            if ($totalTarget6m === 0) {
                $totalTarget6m = $targetRewardUnit * 6;
            }

            $pctCumulative = $totalTarget6m > 0 ? round(($totalUnits6m / $totalTarget6m) * 100, 1) : 0;
            $avgUnitsPerMonth = round($totalUnits6m / 6, 1);

            $prospect6mQuery = Prospect::where('user_id', $s->id)
                ->whereBetween('created_at', [$recapStartDate, $recapEndDate]);
            if (!$includeDemo) {
                $prospect6mQuery->where('is_demo', false);
            }
            $totalProspects6m = (clone $prospect6mQuery)->count();
            $dealProspects6m = (clone $prospect6mQuery)->where(function ($q) use ($statusDealId) {
                $q->where('status_prospek_id', $statusDealId)
                  ->orWhereHas('salesTransactions', fn($st) => $st->where('status_transaksi', 'valid'));
            })->count();

            $conversionRate6m = $totalProspects6m > 0 ? round(($dealProspects6m / $totalProspects6m) * 100, 1) : 0;

            $monthsReachedTarget = 0;
            $monthlyUnitsBreakdown = [];
            $monthlyDealsBreakdown = [];
            $monthlyProspectsBreakdown = [];

            foreach ($sixMonthsList as $mInfo) {
                $mTrxQ = SalesTransaction::valid()
                    ->where('user_id', $s->id)
                    ->whereBetween('tanggal_transaksi', [$mInfo['start']->toDateString(), $mInfo['end']->toDateString()]);
                if (!$includeDemo) {
                    $mTrxQ->where('is_demo', false);
                }
                $mUnits = (int) $mTrxQ->sum('jumlah_unit');
                $monthlyUnitsBreakdown[] = $mUnits;

                if ($mUnits >= $targetRewardUnit) {
                    $monthsReachedTarget++;
                }

                $mProsQ = Prospect::where('user_id', $s->id)
                    ->whereBetween('created_at', [$mInfo['start'], $mInfo['end']]);
                if (!$includeDemo) {
                    $mProsQ->where('is_demo', false);
                }
                $mProspectCount = (clone $mProsQ)->count();
                $mDealCount = (clone $mProsQ)->where(function ($q) use ($statusDealId) {
                    $q->where('status_prospek_id', $statusDealId)
                      ->orWhereHas('salesTransactions', fn($st) => $st->where('status_transaksi', 'valid'));
                })->count();

                $monthlyProspectsBreakdown[] = $mProspectCount;
                $monthlyDealsBreakdown[] = $mDealCount;
            }

            return [
                'user' => $s,
                'total_prospects_6m' => $totalProspects6m,
                'deal_prospects_6m' => $dealProspects6m,
                'conversion_rate_6m' => $conversionRate6m,
                'total_units_6m' => $totalUnits6m,
                'total_target_6m' => $totalTarget6m,
                'pct_cumulative' => $pctCumulative,
                'avg_units_per_month' => $avgUnitsPerMonth,
                'months_reached_target' => $monthsReachedTarget,
                'total_omset_6m' => $totalOmset6m,
                'monthly_units' => $monthlyUnitsBreakdown,
                'monthly_deals' => $monthlyDealsBreakdown,
                'monthly_prospects' => $monthlyProspectsBreakdown,
            ];
        });

        // Urutkan Peringkat 6 Bulan Kumulatif
        $ranked6m = $sixMonthMetrics->sort(function ($a, $b) {
            if ($a['total_units_6m'] !== $b['total_units_6m']) {
                return $b['total_units_6m'] <=> $a['total_units_6m'];
            }
            return $b['total_omset_6m'] <=> $a['total_omset_6m'];
        })->values();

        $recapLeaderboard = $ranked6m->map(function ($item, $idx) use ($ranked6m) {
            if ($idx > 0 && $item['total_units_6m'] === $ranked6m[$idx - 1]['total_units_6m']) {
                $item['rank'] = $ranked6m[$idx - 1]['rank'] ?? ($idx + 1);
            } else {
                $item['rank'] = $idx + 1;
            }
            return $item;
        });

        // Sales Terbaik 6 Bulan
        $salesTerbaik6Bulan = $recapLeaderboard->first();

        // 3. DATA GRAFIK 6 BULAN (CHART.JS)
        $chartColors = [
            0 => ['border' => '#0A4DF3', 'bg' => 'rgba(10,77,243,0.15)'],   // Yamaha Blue
            1 => ['border' => '#10b981', 'bg' => 'rgba(16,185,129,0.15)'],  // Emerald
            2 => ['border' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.15)'],  // Amber
            3 => ['border' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.15)'],  // Purple
        ];

        $chart6mSalesDatasets = [];
        foreach ($recapLeaderboard as $idx => $sRecap) {
            $color = $chartColors[$idx % count($chartColors)];
            $chart6mSalesDatasets[] = [
                'label' => $sRecap['user']->nama,
                'data' => $sRecap['monthly_units'],
                'borderColor' => $color['border'],
                'backgroundColor' => $color['bg'],
                'borderWidth' => 2,
                'fill' => false,
                'tension' => 0.35,
                'pointRadius' => 5,
                'pointHoverRadius' => 7,
            ];
        }

        $chart6mSales = [
            'labels' => array_column($sixMonthsList, 'label'),
            'datasets' => $chart6mSalesDatasets,
        ];

        $teamMonthlyProspects = [];
        $teamMonthlyDeals = [];
        $teamMonthlyRates = [];

        foreach ($sixMonthsList as $mIdx => $mInfo) {
            $pSum = 0;
            $dSum = 0;
            foreach ($recapLeaderboard as $sRecap) {
                $pSum += $sRecap['monthly_prospects'][$mIdx];
                $dSum += $sRecap['monthly_deals'][$mIdx];
            }
            $teamMonthlyProspects[] = $pSum;
            $teamMonthlyDeals[] = $dSum;
            $teamMonthlyRates[] = $pSum > 0 ? round(($dSum / $pSum) * 100, 1) : 0;
        }

        $chart6mProspectDeal = [
            'labels' => array_column($sixMonthsList, 'label'),
            'prospects' => $teamMonthlyProspects,
            'deals' => $teamMonthlyDeals,
            'rates' => $teamMonthlyRates,
        ];

        // Chart data: comparison per-sales (for supervisor overview)
        $chartSalesComparisonDatasets = [];
        foreach ($recapLeaderboard as $idx => $sRecap) {
            $color = $chartColors[$idx % count($chartColors)];
            $chartSalesComparisonDatasets[] = [
                'label'           => $sRecap['user']->nama,
                'data'            => $sRecap['monthly_units'],
                'borderColor'     => $color['border'],
                'backgroundColor' => $color['bg'],
                'borderWidth'     => 2,
                'fill'            => false,
                'tension'         => 0.35,
                'pointRadius'     => 4,
                'pointHoverRadius'=> 6,
            ];
        }
        $chartSalesComparison = [
            'labels'   => array_column($sixMonthsList, 'label'),
            'datasets' => $chartSalesComparisonDatasets,
        ];

        // ==========================================
        // DATA PROSPEK PERIODE INI (SUPERVISOR & SALES)
        // ==========================================
        $prospekQuery = Prospect::whereBetween('created_at', [$startDate, $endDate]);
        if (!$includeDemo) {
            $prospekQuery->where('is_demo', false);
        }
        $totalProspek = (clone $prospekQuery)->count();
        $prospekDeal  = (clone $prospekQuery)->where('status_prospek_id', $statusDealId)->count();
        $prospekBatal = (clone $prospekQuery)->where('status_prospek_id', $statusBatalId)->count();
        $prospekProses = $totalProspek - $prospekDeal - $prospekBatal;
        $persentaseKonversi = $totalProspek > 0 ? round(($prospekDeal / $totalProspek) * 100, 1) : 0;

        // Konversi per-sales (untuk supervisor) — key harus cocok dengan view supervisor.blade.php
        $prospectConversionBySales = $leaderboard->map(function ($item) use ($startDate, $endDate, $includeDemo, $statusDealId, $statusBatalId) {
            $totalP = $item['total_prospects'];
            $deal   = $item['deal_prospects'];

            // Hitung batal langsung dari query
            $batalQ = Prospect::where('user_id', $item['user']->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status_prospek_id', $statusBatalId);
            if (!$includeDemo) {
                $batalQ->where('is_demo', false);
            }
            $batal  = $batalQ->count();
            $proses = max(0, $totalP - $deal - $batal);

            return [
                'sales'           => $item['user'],   // view pakai 'sales'
                'user'            => $item['user'],
                'total_prospek'   => $totalP,          // view pakai 'total_prospek'
                'deal'            => $deal,             // view pakai 'deal'
                'proses'          => $proses,           // view pakai 'proses'
                'batal'           => $batal,            // view pakai 'batal'
                'conversion_rate' => $item['conversion_rate'],
                'units'           => $item['units'],
                'rank'            => $item['rank'],
            ];
        })->values();

        // ==========================================
        // 1. DASHBOARD SUPERVISOR
        // ==========================================
        if ($currentUser->isSupervisor()) {

            // Rapat & Temuan Lapangan
            $upcomingMeetingsCount = Meeting::where('tanggal', '>=', now()->toDateString())
                ->whereIn('status', ['dijadwalkan', 'berlangsung'])
                ->count();

            $upcomingMeetings = Meeting::where('tanggal', '>=', now()->toDateString())
                ->whereIn('status', ['dijadwalkan', 'berlangsung'])
                ->orderBy('tanggal', 'asc')
                ->take(3)
                ->get();

            $pendingFindingsCount = FieldFinding::whereIn('status_tindak_lanjut', ['menunggu', 'sedang_diproses'])->count();
            $recentFindings = FieldFinding::with(['user', 'motorcycle'])
                ->whereIn('status_tindak_lanjut', ['menunggu', 'sedang_diproses'])
                ->orderBy('tanggal_temuan', 'desc')
                ->take(4)
                ->get();

            // Demo data counter for quick supervisor inspection
            $demoDataCount = SalesTransaction::where('is_demo', true)->count() +
                             Prospect::where('is_demo', true)->count() +
                             ProspectActivity::where('is_demo', true)->count() +
                             SalesTarget::where('is_demo', true)->count();

            return view('dashboard.supervisor', compact(
                'totalSalesAktif',
                'totalUnitTerjual',
                'totalPendapatan',
                'totalTargetPenjualan',
                'persentaseCapaian',
                'salesTerbaikBulanIni',
                'countReachedTarget',
                'countReachedReward',
                'countAlmostTarget',
                'upcomingMeetingsCount',
                'upcomingMeetings',
                'pendingFindingsCount',
                'recentFindings',
                'leaderboard',
                'chartSalesComparison',
                'recapPeriodeLabel',
                'recapLeaderboard',
                'salesTerbaik6Bulan',
                'chart6mSales',
                'chart6mProspectDeal',
                'recapEndMonth',
                'recapEndYear',
                'totalProspek',
                'prospekDeal',
                'prospekProses',
                'prospekBatal',
                'persentaseKonversi',
                'prospectConversionBySales',
                'periodeLabel',
                'filterType',
                'startDate',
                'endDate',
                'bulan',
                'tahun',
                'targetRewardUnit',
                'dataMode',
                'includeDemo',
                'demoDataCount'
            ));
        }

        // ==========================================
        // 2. DASHBOARD SALES
        // ==========================================
        $myUnitsQuery = SalesTransaction::valid()
            ->where('user_id', $currentUser->id)
            ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()]);
        if (!$includeDemo) {
            $myUnitsQuery->where('is_demo', false);
        }
        $myUnits = (int) $myUnitsQuery->sum('jumlah_unit');
        $myOmset = (int) (clone $myUnitsQuery)->sum('total_harga');

        $myTargetQuery = SalesTarget::where('user_id', $currentUser->id)
            ->where('tanggal_mulai', '<=', $endDate->toDateString())
            ->where('tanggal_selesai', '>=', $startDate->toDateString());
        if (!$includeDemo) {
            $myTargetQuery->where('is_demo', false);
        }
        $myTarget = (int) $myTargetQuery->sum('target_unit');
        if ($myTarget === 0) {
            $myTarget = $targetRewardUnit;
        }

        $myPercentage = $myTarget > 0 ? round(($myUnits / $myTarget) * 100, 1) : 0;
        $myRemaining = max(0, $myTarget - $myUnits);

        // Progress Menuju Reward 15 Unit
        $rewardProgressPercentage = min(100, round(($myUnits / $targetRewardUnit) * 100, 1));
        $rewardRemaining = max(0, $targetRewardUnit - $myUnits);
        $rewardEligible = $myUnits >= $targetRewardUnit;

        $myStatusKinerja = PerformanceCriterion::determineCategory($myPercentage);

        // Peringkat Sales Periode Berjalan
        $allUnitsQuery = SalesTransaction::valid()
            ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()]);
        if (!$includeDemo) {
            $allUnitsQuery->where('is_demo', false);
        }
        $allSalesUnits = $allUnitsQuery->selectRaw('user_id, sum(jumlah_unit) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->pluck('total', 'user_id')
            ->toArray();

        $myRank = 1;
        $rankFound = false;
        foreach ($allSalesUnits as $uid => $units) {
            if ($uid == $currentUser->id) {
                $rankFound = true;
                break;
            }
            $myRank++;
        }
        if (!$rankFound) {
            $myRank = count($allSalesUnits) + 1;
        }

        // Analisis Konversi Prospek Pribadi
        $myProspectQuery = Prospect::where('user_id', $currentUser->id)
            ->whereBetween('created_at', [$startDate, $endDate]);
        if (!$includeDemo) {
            $myProspectQuery->where('is_demo', false);
        }
        $myTotalProspects = (clone $myProspectQuery)->count();

        $myDealProspects = (clone $myProspectQuery)->where(function ($q) use ($statusDealId) {
            $q->where('status_prospek_id', $statusDealId)
              ->orWhereHas('salesTransactions', fn($st) => $st->where('status_transaksi', 'valid'));
        })->count();

        $myBatalProspects = (clone $myProspectQuery)->where('status_prospek_id', $statusBatalId)
            ->whereDoesntHave('salesTransactions', fn($st) => $st->where('status_transaksi', 'valid'))
            ->count();

        $myProcessProspects = max(0, $myTotalProspects - $myDealProspects - $myBatalProspects);
        $myConversionRate = $myTotalProspects > 0 ? round(($myDealProspects / $myTotalProspects) * 100, 1) : 0;

        // Rekap 6 Bulan Pribadi Sales
        $my6mStartDate = Carbon::createFromDate($tahun, $bulan, 1)->subMonths(5)->startOfMonth();
        $my6mEndDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $my6mUnits = (int) SalesTransaction::valid()
            ->where('user_id', $currentUser->id)
            ->whereBetween('tanggal_transaksi', [$my6mStartDate->toDateString(), $my6mEndDate->toDateString()])
            ->sum('jumlah_unit');

        $my6mTarget = 15 * 6;
        $my6mPct = round(($my6mUnits / $my6mTarget) * 100, 1);
        $my6mAvg = round($my6mUnits / 6, 1);

        $my6mProspects = Prospect::where('user_id', $currentUser->id)
            ->whereBetween('created_at', [$my6mStartDate, $my6mEndDate])
            ->count();

        $my6mDeals = Prospect::where('user_id', $currentUser->id)
            ->whereBetween('created_at', [$my6mStartDate, $my6mEndDate])
            ->where(function ($q) use ($statusDealId) {
                $q->where('status_prospek_id', $statusDealId)
                  ->orWhereHas('salesTransactions', fn($st) => $st->where('status_transaksi', 'valid'));
            })->count();

        $my6mConvRate = $my6mProspects > 0 ? round(($my6mDeals / $my6mProspects) * 100, 1) : 0;

        // Rapat & Agenda Pribadi
        $nextMeeting = Meeting::where('tanggal', '>=', now()->toDateString())
            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->whereHas('attendances', fn($q) => $q->where('user_id', $currentUser->id))
            ->orderBy('tanggal', 'asc')
            ->first();

        $myAttendance = null;
        if ($nextMeeting) {
            $myAttendance = MeetingAttendance::where('meeting_id', $nextMeeting->id)
                ->where('user_id', $currentUser->id)
                ->first();
        }

        $myPendingFindings = FieldFinding::where('user_id', $currentUser->id)
            ->whereIn('status_tindak_lanjut', ['menunggu', 'sedang_diproses'])
            ->orderBy('tanggal_temuan', 'desc')
            ->take(4)
            ->get();

        $latestEvaluation = PerformanceEvaluation::where('user_id', $currentUser->id)
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->first();

        $myRecentTransactions = SalesTransaction::with('motorcycle')
            ->where('user_id', $currentUser->id)
            ->orderBy('tanggal_transaksi', 'desc')
            ->take(5)
            ->get();

        // Cari peringkat resmi dalam leaderboard sistem
        $myLeaderboardItem = $leaderboard->firstWhere('user.id', $currentUser->id);
        if ($myLeaderboardItem) {
            $myRank = $myLeaderboardItem['rank'];
        }

        $myRecapItem = $recapLeaderboard->firstWhere('user.id', $currentUser->id);
        $my6mRank = $myRecapItem ? $myRecapItem['rank'] : 1;

        // Cek status kemenangan pengguna yang sedang login
        $isWinnerMonth = ($salesTerbaikBulanIni && isset($salesTerbaikBulanIni['user']) && $salesTerbaikBulanIni['user']->id === $currentUser->id);
        $isWinner6m = ($salesTerbaik6Bulan && isset($salesTerbaik6Bulan['user']) && $salesTerbaik6Bulan['user']->id === $currentUser->id);

        // Ringkasan ranking aman untuk ditampilkan kepada Sales
        $safeLeaderboard = $leaderboard->take(5);
        $safeRecapLeaderboard = $recapLeaderboard->take(5);

        return view('dashboard.sales', compact(
            'myUnits',
            'myTarget',
            'myPercentage',
            'myRemaining',
            'myOmset',
            'targetRewardUnit',
            'rewardProgressPercentage',
            'rewardRemaining',
            'rewardEligible',
            'myStatusKinerja',
            'myRank',
            'my6mRank',
            'myTotalProspects',
            'myDealProspects',
            'myProcessProspects',
            'myBatalProspects',
            'myConversionRate',
            'my6mUnits',
            'my6mTarget',
            'my6mPct',
            'my6mAvg',
            'my6mProspects',
            'my6mDeals',
            'my6mConvRate',
            'salesTerbaikBulanIni',
            'isWinnerMonth',
            'salesTerbaik6Bulan',
            'isWinner6m',
            'safeLeaderboard',
            'safeRecapLeaderboard',
            'nextMeeting',
            'myAttendance',
            'myPendingFindings',
            'latestEvaluation',
            'myRecentTransactions',
            'periodeLabel',
            'filterType',
            'bulan',
            'tahun',
            'dataMode'
        ));
    }

    /**
     * Membersihkan seluruh data demo simulasi secara aman (hanya is_demo = true).
     * Akun asli (Budi Santoso, Siti Rahmawati, Kusmayadi) dan data operasional tetap 100% utuh.
     */
    public function cleanDemoData(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang berhak membersihkan data demo.');
        }

        DB::transaction(function () {
            ProspectActivity::where('is_demo', true)->delete();
            MeetingAttendance::where('is_demo', true)->delete();
            Meeting::where('is_demo', true)->delete();
            FieldFinding::where('is_demo', true)->delete();
            PerformanceEvaluation::where('is_demo', true)->delete();
            SalesReward::where('is_demo', true)->delete();
            RewardProgram::where('is_demo', true)->delete();
            SalesTransaction::where('is_demo', true)->forceDelete();
            Prospect::where('is_demo', true)->forceDelete();
            SalesTarget::where('is_demo', true)->delete();
            
            // Hapus hanya akun demo sales (Doni & Rina), akun asli Budi & Siti memiliki is_demo = false sehingga aman
            User::where('is_demo', true)->delete();
        });

        return redirect()->route('dashboard', ['data_mode' => 'real'])
            ->with('success', 'Seluruh data simulasi demo presentasi berhasil dibersihkan! Akun sales dan data operasional asli tetap aman dan utuh.');
    }
}
