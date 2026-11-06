<?php

namespace App\Http\Controllers;

use App\Models\FieldFinding;
use App\Models\MeetingAttendance;
use App\Models\Motorcycle;
use App\Models\PerformanceCriterion;
use App\Models\PerformanceEvaluation;
use App\Models\RewardProgram;
use App\Models\SalesReward;
use App\Models\SalesTarget;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    // ==========================================
    // 1. LAPORAN PENJUALAN UMUM
    // ==========================================
    public function sales(Request $request): View
    {
        $currentUser = Auth::user();
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        $salesId = $request->input('sales_id');
        $motorcycleId = $request->input('motorcycle_id');
        $status = $request->input('status', 'valid');

        $query = SalesTransaction::with(['sales', 'motorcycle', 'color']);

        if (!$currentUser->isSupervisor()) {
            $query->where('user_id', $currentUser->id);
        } elseif (!empty($salesId)) {
            $query->where('user_id', $salesId);
        }

        if (!empty($motorcycleId)) {
            $query->where('sepeda_motor_id', $motorcycleId);
        }

        if (!empty($status)) {
            $query->where('status_transaksi', $status);
        }

        $minggu = $request->input('minggu');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->input('start_date'), $request->input('end_date')]);
            $periodeText = Carbon::parse($request->input('start_date'))->format('d/m/Y') . ' - ' . Carbon::parse($request->input('end_date'))->format('d/m/Y');
        } elseif (!empty($minggu)) {
            $monthObj = Carbon::createFromDate($tahun, $bulan, 1);
            $wStart = match ((int)$minggu) {
                1 => $monthObj->copy()->startOfMonth(),
                2 => $monthObj->copy()->startOfMonth()->addDays(7),
                3 => $monthObj->copy()->startOfMonth()->addDays(14),
                4 => $monthObj->copy()->startOfMonth()->addDays(21),
                default => $monthObj->copy()->startOfMonth()->addDays(28),
            };
            $wEnd = match ((int)$minggu) {
                1 => $monthObj->copy()->startOfMonth()->addDays(6),
                2 => $monthObj->copy()->startOfMonth()->addDays(13),
                3 => $monthObj->copy()->startOfMonth()->addDays(20),
                4 => $monthObj->copy()->startOfMonth()->addDays(27),
                default => $monthObj->copy()->endOfMonth(),
            };
            $wEnd = min($wEnd, $monthObj->copy()->endOfMonth());
            $query->whereBetween('tanggal_transaksi', [$wStart->toDateString(), $wEnd->toDateString()]);
            $namaBulan = $monthObj->locale('id')->isoFormat('MMMM Y');
            $periodeText = "Minggu ke-{$minggu} ({$wStart->format('d/m')} - {$wEnd->format('d/m/Y')}), Bulan {$namaBulan}";
        } else {
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM Y');
            $periodeText = "Bulan {$namaBulan}";
        }

        $transactions = $query->orderBy('tanggal_transaksi', 'desc')->paginate(20)->withQueryString();

        // Summary calculations
        $summaryQuery = clone $query;
        $totalTransaksi = (clone $summaryQuery)->count();
        $totalUnit = (clone $summaryQuery)->where('status_transaksi', 'valid')->sum('jumlah_unit');
        $totalPendapatan = (clone $summaryQuery)->where('status_transaksi', 'valid')->sum('total_harga');

        // Target calculation
        $targetQuery = SalesTarget::where('tahun', $tahun);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $targetQuery->where('tanggal_mulai', '<=', $request->input('end_date'))
                        ->where('tanggal_selesai', '>=', $request->input('start_date'));
        } else {
            $targetQuery->where('bulan', $bulan);
        }
        if (!$currentUser->isSupervisor()) {
            $targetQuery->where('user_id', $currentUser->id);
        } elseif (!empty($salesId)) {
            $targetQuery->where('user_id', $salesId);
        }
        $totalTarget = $targetQuery->sum('target_unit');
        $persentase = $totalTarget > 0 ? round(($totalUnit / $totalTarget) * 100, 1) : 0;

        $motorcycles = Motorcycle::where('status_aktif', true)->orderBy('nama_model')->get();
        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        return view('reports.sales', compact(
            'transactions',
            'totalTransaksi',
            'totalUnit',
            'totalPendapatan',
            'totalTarget',
            'persentase',
            'periodeText',
            'motorcycles',
            'salesList',
            'bulan',
            'tahun',
            'salesId',
            'motorcycleId',
            'status',
            'minggu'
        ));
    }

    public function exportSalesWord(Request $request): StreamedResponse
    {
        $currentUser = Auth::user();
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        $salesId = $request->input('sales_id');
        $motorcycleId = $request->input('motorcycle_id');
        $status = $request->input('status', 'valid');
        $minggu = $request->input('minggu');

        $query = SalesTransaction::with(['sales', 'motorcycle', 'color']);

        if (!$currentUser->isSupervisor()) {
            $query->where('user_id', $currentUser->id);
        } elseif (!empty($salesId)) {
            $query->where('user_id', $salesId);
        }

        if (!empty($motorcycleId)) {
            $query->where('sepeda_motor_id', $motorcycleId);
        }

        if (!empty($status)) {
            $query->where('status_transaksi', $status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->input('start_date'), $request->input('end_date')]);
            $periodeText = Carbon::parse($request->input('start_date'))->format('d/m/Y') . ' - ' . Carbon::parse($request->input('end_date'))->format('d/m/Y');
        } elseif (!empty($minggu)) {
            $monthObj = Carbon::createFromDate($tahun, $bulan, 1);
            $wStart = match ((int)$minggu) {
                1 => $monthObj->copy()->startOfMonth(),
                2 => $monthObj->copy()->startOfMonth()->addDays(7),
                3 => $monthObj->copy()->startOfMonth()->addDays(14),
                4 => $monthObj->copy()->startOfMonth()->addDays(21),
                default => $monthObj->copy()->startOfMonth()->addDays(28),
            };
            $wEnd = match ((int)$minggu) {
                1 => $monthObj->copy()->startOfMonth()->addDays(6),
                2 => $monthObj->copy()->startOfMonth()->addDays(13),
                3 => $monthObj->copy()->startOfMonth()->addDays(20),
                4 => $monthObj->copy()->startOfMonth()->addDays(27),
                default => $monthObj->copy()->endOfMonth(),
            };
            $wEnd = min($wEnd, $monthObj->copy()->endOfMonth());
            $query->whereBetween('tanggal_transaksi', [$wStart->toDateString(), $wEnd->toDateString()]);
            $namaBulan = $monthObj->locale('id')->isoFormat('MMMM Y');
            $periodeText = "Minggu ke-{$minggu} ({$wStart->format('d/m')} - {$wEnd->format('d/m/Y')}), Bulan {$namaBulan}";
        } else {
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
            $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM Y');
            $periodeText = "Bulan {$namaBulan}";
        }

        $transactions = $query->orderBy('tanggal_transaksi', 'asc')->get();

        $totalUnit = $transactions->where('status_transaksi', 'valid')->sum('jumlah_unit');
        $totalPendapatan = $transactions->where('status_transaksi', 'valid')->sum('total_harga');

        // Create Word Document
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(10);

        $section = $phpWord->addSection([
            'marginTop' => 800,
            'marginBottom' => 800,
            'marginLeft' => 1000,
            'marginRight' => 1000,
        ]);

        // Header
        $section->addText('SIMAKIS — SISTEM MANAJEMEN KINERJA SALES', ['bold' => true, 'size' => 14, 'color' => '0A4DF3'], ['alignment' => Jc::CENTER]);
        $section->addText('DEALER YAMAHA JG MOTOR PURWAKARTA', ['bold' => true, 'size' => 11, 'color' => '334155'], ['alignment' => Jc::CENTER]);
        $section->addText('LAPORAN TRANSAKSI PENJUALAN MOTOR', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addText('Periode: ' . $periodeText . ' | Dicetak pada: ' . now()->locale('id')->isoFormat('D MMMM Y, HH:mm'), ['italic' => true, 'size' => 9, 'color' => '64748B'], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);

        // Summary Box
        $summaryTable = $section->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'cellMargin' => 80, 'alignment' => JcTable::CENTER]);
        $summaryTable->addRow();
        $summaryTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Total Transaksi Valid', ['bold' => true]);
        $summaryTable->addCell(6000)->addText($transactions->where('status_transaksi', 'valid')->count() . ' Transaksi');
        $summaryTable->addRow();
        $summaryTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Total Unit Terjual', ['bold' => true]);
        $summaryTable->addCell(6000)->addText($totalUnit . ' Unit', ['bold' => true, 'color' => '0A4DF3']);
        $summaryTable->addRow();
        $summaryTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Total Nilai Penjualan', ['bold' => true]);
        $summaryTable->addCell(6000)->addText('Rp ' . number_format($totalPendapatan, 0, ',', '.'));

        $section->addTextBreak(1);

        // Data Table
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'CBD5E1',
            'cellMargin' => 70,
            'alignment' => JcTable::CENTER
        ]);

        // Header Row
        $table->addRow();
        $table->addCell(600, ['bgColor' => '0A4DF3'])->addText('No', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(1800, ['bgColor' => '0A4DF3'])->addText('No. Transaksi', ['bold' => true, 'color' => 'FFFFFF']);
        $table->addCell(1300, ['bgColor' => '0A4DF3'])->addText('Tanggal', ['bold' => true, 'color' => 'FFFFFF']);
        $table->addCell(2000, ['bgColor' => '0A4DF3'])->addText('Sales', ['bold' => true, 'color' => 'FFFFFF']);
        $table->addCell(2500, ['bgColor' => '0A4DF3'])->addText('Tipe / Model Motor', ['bold' => true, 'color' => 'FFFFFF']);
        $table->addCell(800, ['bgColor' => '0A4DF3'])->addText('Unit', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(2000, ['bgColor' => '0A4DF3'])->addText('Total Harga', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::RIGHT]);
        $table->addCell(1200, ['bgColor' => '0A4DF3'])->addText('Status', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);

        if ($transactions->isEmpty()) {
            $table->addRow();
            $table->addCell(12200, ['gridSpan' => 8])->addText('Tidak ada data transaksi penjualan pada periode yang dipilih.', ['italic' => true], ['alignment' => Jc::CENTER]);
        } else {
            foreach ($transactions as $idx => $t) {
                $table->addRow();
                $table->addCell(600)->addText($idx + 1, [], ['alignment' => Jc::CENTER]);
                $table->addCell(1800)->addText($t->nomor_transaksi);
                $table->addCell(1300)->addText(Carbon::parse($t->tanggal_transaksi)->format('d/m/Y'));
                $table->addCell(2000)->addText($t->sales->nama ?? '-');
                $motorText = ($t->motorcycle->nama_model ?? '-') . ($t->nama_warna ? " ({$t->nama_warna})" : '');
                $table->addCell(2500)->addText($motorText);
                $table->addCell(800)->addText($t->jumlah_unit, [], ['alignment' => Jc::CENTER]);
                $table->addCell(2000)->addText('Rp ' . number_format($t->total_harga, 0, ',', '.'), [], ['alignment' => Jc::RIGHT]);
                $table->addCell(1200)->addText(ucfirst($t->status_transaksi), ['bold' => true, 'color' => $t->status_transaksi === 'valid' ? '059669' : 'DC2626'], ['alignment' => Jc::CENTER]);
            }
        }

        // Signature section
        $section->addTextBreak(2);
        $sigTable = $section->addTable(['borderSize' => 0, 'alignment' => JcTable::END]);
        $sigTable->addRow();
        $sigCell = $sigTable->addCell(4000);
        $sigCell->addText('Purwakarta, ' . now()->locale('id')->isoFormat('D MMMM Y'), [], ['alignment' => Jc::CENTER]);
        $sigCell->addText('Mengetahui / Mengesahkan,', [], ['alignment' => Jc::CENTER]);
        $sigCell->addText('Supervisor Sales SIMAKIS', ['bold' => true], ['alignment' => Jc::CENTER]);
        $sigCell->addTextBreak(3);
        $sigCell->addText('( ' . $currentUser->nama . ' )', ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::CENTER]);

        $fileName = 'Laporan_Penjualan_' . str_replace(' ', '_', $periodeText) . '_' . date('Ymd_His') . '.docx';

        ActivityLogService::log(
            'ekspor',
            'laporan',
            "Mengekspor Laporan Penjualan Word (Periode: {$periodeText})",
            null,
            ['periode' => $periodeText, 'file' => $fileName]
        );

        return response()->streamDownload(function () use ($phpWord) {
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    // ==========================================
    // 2. LAPORAN PENJUALAN PER SALES
    // ==========================================
    public function salesPerson(Request $request): View
    {
        $currentUser = Auth::user();
        $bulan = (int) ($request->input('bulan', date('n')));
        $tahun = (int) ($request->input('tahun', date('Y')));
        $salesId = $request->input('sales_id');

        if (!$currentUser->isSupervisor()) {
            $salesId = $currentUser->id;
        }

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $selectedSales = $salesId ? User::find($salesId) : null;

        // Breakdown by motorcycle for this sales
        $breakdown = collect();
        $totalUnit = 0;
        $totalNilai = 0;
        $target = null;
        $targetUnit = 0;
        $persentase = 0;
        $sisaUnit = 0;
        $peringkat = '-';

        if ($selectedSales) {
            $transactions = SalesTransaction::valid()
                ->with(['motorcycle', 'color'])
                ->where('user_id', $selectedSales->id)
                ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
                ->get();

            $totalUnit = $transactions->sum('jumlah_unit');
            $totalNilai = $transactions->sum('total_harga');

            $breakdown = $transactions->groupBy('sepeda_motor_id')->map(function ($items) {
                return [
                    'model' => $items->first()->motorcycle->nama_model ?? 'Model Tidak Diketahui',
                    'total_unit' => $items->sum('jumlah_unit'),
                    'total_harga' => $items->sum('total_harga'),
                    'warna_list' => $items->pluck('nama_warna')->filter()->unique()->implode(', '),
                ];
            });

            // Target
            $target = SalesTarget::where('user_id', $selectedSales->id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->first();

            $targetUnit = $target ? $target->target_unit : 0;
            $persentase = $targetUnit > 0 ? round(($totalUnit / $targetUnit) * 100, 1) : 0;
            $sisaUnit = max(0, $targetUnit - $totalUnit);

            // Calculate current rank among all sales in this period
            $allSalesUnits = SalesTransaction::valid()
                ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
                ->selectRaw('user_id, sum(jumlah_unit) as total')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->pluck('total', 'user_id')
                ->toArray();

            $rank = 1;
            foreach ($allSalesUnits as $uid => $units) {
                if ($uid == $selectedSales->id) {
                    $peringkat = '#' . $rank;
                    break;
                }
                $rank++;
            }
            if ($peringkat === '-' && $selectedSales) {
                $peringkat = '#' . (count($allSalesUnits) + 1);
            }
        }

        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        return view('reports.sales_person', compact(
            'selectedSales',
            'salesList',
            'bulan',
            'tahun',
            'breakdown',
            'totalUnit',
            'totalNilai',
            'targetUnit',
            'persentase',
            'sisaUnit',
            'peringkat'
        ));
    }

    public function exportSalesPersonWord(Request $request): StreamedResponse
    {
        $currentUser = Auth::user();
        $bulan = (int) ($request->input('bulan', date('n')));
        $tahun = (int) ($request->input('tahun', date('Y')));
        $salesId = $request->input('sales_id');

        if (!$currentUser->isSupervisor()) {
            $salesId = $currentUser->id;
        }

        $selectedSales = User::findOrFail($salesId);
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $transactions = SalesTransaction::valid()
            ->with(['motorcycle', 'color'])
            ->where('user_id', $selectedSales->id)
            ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $totalUnit = $transactions->sum('jumlah_unit');
        $totalNilai = $transactions->sum('total_harga');

        $breakdown = $transactions->groupBy('sepeda_motor_id')->map(function ($items) {
            return [
                'model' => $items->first()->motorcycle->nama_model ?? 'Model Tidak Diketahui',
                'total_unit' => $items->sum('jumlah_unit'),
                'total_harga' => $items->sum('total_harga'),
                'warna_list' => $items->pluck('nama_warna')->filter()->unique()->implode(', '),
            ];
        });

        $target = SalesTarget::where('user_id', $selectedSales->id)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        $targetUnit = $target ? $target->target_unit : 0;
        $persentase = $targetUnit > 0 ? round(($totalUnit / $targetUnit) * 100, 1) : 0;
        $sisaUnit = max(0, $targetUnit - $totalUnit);
        $statusKinerja = PerformanceCriterion::determineCategory($persentase);

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM Y');

        // Document Generation
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(10);

        $section = $phpWord->addSection([
            'marginTop' => 800,
            'marginBottom' => 800,
            'marginLeft' => 1000,
            'marginRight' => 1000,
        ]);

        $section->addText('SIMAKIS — SISTEM MANAJEMEN KINERJA SALES', ['bold' => true, 'size' => 14, 'color' => '0A4DF3'], ['alignment' => Jc::CENTER]);
        $section->addText('LAPORAN PENJUALAN PER SALES INDIVIDUAL', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addText('Periode: Bulan ' . $namaBulan . ' | Sales: ' . $selectedSales->nama, ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);

        // Sales Details Box
        $infoTable = $section->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'cellMargin' => 80, 'alignment' => JcTable::CENTER]);
        $infoTable->addRow();
        $infoTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Nama Sales', ['bold' => true]);
        $infoTable->addCell(7000)->addText($selectedSales->nama . ' (' . $selectedSales->email . ')');
        $infoTable->addRow();
        $infoTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Cabang / Dealer', ['bold' => true]);
        $infoTable->addCell(7000)->addText($selectedSales->cabang ?? 'Yamaha JG Purwakarta');
        $infoTable->addRow();
        $infoTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Target Penjualan', ['bold' => true]);
        $infoTable->addCell(7000)->addText($targetUnit . ' Unit');
        $infoTable->addRow();
        $infoTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Realisasi Penjualan', ['bold' => true]);
        $infoTable->addCell(7000)->addText($totalUnit . ' Unit (Capaian: ' . $persentase . '%)', ['bold' => true, 'color' => '0A4DF3']);
        $infoTable->addRow();
        $infoTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Sisa Unit Menuju Target', ['bold' => true]);
        $infoTable->addCell(7000)->addText($sisaUnit . ' Unit');
        $infoTable->addRow();
        $infoTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Kategori Kinerja', ['bold' => true]);
        $infoTable->addCell(7000)->addText($statusKinerja, ['bold' => true]);

        $section->addTextBreak(1);
        $section->addText('Rincian Penjualan per Tipe Motor:', ['bold' => true, 'size' => 11]);

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'cellMargin' => 70, 'alignment' => JcTable::CENTER]);
        $table->addRow();
        $table->addCell(800, ['bgColor' => '0A4DF3'])->addText('No', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(4000, ['bgColor' => '0A4DF3'])->addText('Model Motor', ['bold' => true, 'color' => 'FFFFFF']);
        $table->addCell(3000, ['bgColor' => '0A4DF3'])->addText('Varian / Warna', ['bold' => true, 'color' => 'FFFFFF']);
        $table->addCell(1200, ['bgColor' => '0A4DF3'])->addText('Unit', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(2500, ['bgColor' => '0A4DF3'])->addText('Total Nilai', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::RIGHT]);

        if ($breakdown->isEmpty()) {
            $table->addRow();
            $table->addCell(11500, ['gridSpan' => 5])->addText('Belum ada transaksi penjualan pada periode ini.', ['italic' => true], ['alignment' => Jc::CENTER]);
        } else {
            $i = 1;
            foreach ($breakdown as $item) {
                $table->addRow();
                $table->addCell(800)->addText($i++, [], ['alignment' => Jc::CENTER]);
                $table->addCell(4000)->addText($item['model']);
                $table->addCell(3000)->addText($item['warna_list'] ?: '-');
                $table->addCell(1200)->addText($item['total_unit'], [], ['alignment' => Jc::CENTER]);
                $table->addCell(2500)->addText('Rp ' . number_format($item['total_harga'], 0, ',', '.'), [], ['alignment' => Jc::RIGHT]);
            }
        }

        $section->addTextBreak(2);
        $sigTable = $section->addTable(['borderSize' => 0, 'alignment' => JcTable::END]);
        $sigTable->addRow();
        $sigCell = $sigTable->addCell(4000);
        $sigCell->addText('Purwakarta, ' . now()->locale('id')->isoFormat('D MMMM Y'), [], ['alignment' => Jc::CENTER]);
        $sigCell->addText('Supervisor Sales SIMAKIS,', ['bold' => true], ['alignment' => Jc::CENTER]);
        $sigCell->addTextBreak(3);
        $sigCell->addText('( ' . $currentUser->nama . ' )', ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::CENTER]);

        $fileName = 'Laporan_Sales_' . str_replace(' ', '_', $selectedSales->nama) . '_' . $namaBulan . '.docx';

        ActivityLogService::log(
            'ekspor',
            'laporan',
            "Mengekspor Laporan Penjualan per Sales ({$selectedSales->nama}, Periode: {$namaBulan} {$tahun})",
            null,
            ['sales' => $selectedSales->nama, 'bulan' => $namaBulan, 'tahun' => $tahun]
        );

        return response()->streamDownload(function () use ($phpWord) {
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    // ==========================================
    // 3. LAPORAN KINERJA SALES
    // ==========================================
    public function performance(Request $request): View
    {
        $currentUser = Auth::user();
        $bulan = (int) ($request->input('bulan', date('n')));
        $tahun = (int) ($request->input('tahun', date('Y')));
        $salesId = $request->input('sales_id');

        if (!$currentUser->isSupervisor()) {
            $salesId = $currentUser->id;
        }

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $selectedSales = $salesId ? User::find($salesId) : null;

        $reportData = null;

        if ($selectedSales) {
            // Realisasi unit
            $realisasiUnit = (int) SalesTransaction::valid()
                ->where('user_id', $selectedSales->id)
                ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
                ->sum('jumlah_unit');

            // Target
            $target = SalesTarget::where('user_id', $selectedSales->id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->first();

            $targetUnit = $target ? $target->target_unit : 0;
            $persentase = $targetUnit > 0 ? round(($realisasiUnit / $targetUnit) * 100, 1) : 0;
            $statusKinerja = PerformanceCriterion::determineCategory($persentase);

            // Reward
            $rewardProgram = RewardProgram::where('is_active', true)->first();
            $rewardTarget = $rewardProgram ? $rewardProgram->target_minimal_unit : 15;
            $salesReward = SalesReward::where('user_id', $selectedSales->id)
                ->where('periode_tahun', $tahun)
                ->where('periode_bulan', $bulan)
                ->first();

            // Field findings count
            $totalFindings = FieldFinding::where('user_id', $selectedSales->id)
                ->whereBetween('tanggal_temuan', [$startDate->toDateString(), $endDate->toDateString()])
                ->count();

            $resolvedFindings = FieldFinding::where('user_id', $selectedSales->id)
                ->whereBetween('tanggal_temuan', [$startDate->toDateString(), $endDate->toDateString()])
                ->where('status_tindak_lanjut', 'selesai')
                ->count();

            // Meetings attendance count
            $attendanceCount = MeetingAttendance::where('user_id', $selectedSales->id)
                ->whereHas('meeting', fn($q) => $q->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]))
                ->where('status_kehadiran', 'hadir')
                ->count();

            $totalMeetings = MeetingAttendance::where('user_id', $selectedSales->id)
                ->whereHas('meeting', fn($q) => $q->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]))
                ->count();

            // Evaluation from Supervisor
            $evaluation = PerformanceEvaluation::where('user_id', $selectedSales->id)
                ->where('periode_tahun', $tahun)
                ->where('periode_bulan', $bulan)
                ->first();

            // Compute rank
            $allRanks = SalesTransaction::valid()
                ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
                ->selectRaw('user_id, sum(jumlah_unit) as total')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->pluck('total', 'user_id')
                ->toArray();

            $rankIndex = 1;
            $rankString = '#' . (count($allRanks) + 1);
            foreach ($allRanks as $uid => $units) {
                if ($uid == $selectedSales->id) {
                    $rankString = '#' . $rankIndex;
                    break;
                }
                $rankIndex++;
            }

            $reportData = [
                'target_unit' => $targetUnit,
                'realisasi_unit' => $realisasiUnit,
                'persentase' => $persentase,
                'peringkat' => $rankString,
                'status_kinerja' => $statusKinerja,
                'reward_target' => $rewardTarget,
                'reward_status' => $salesReward ? $salesReward->status_label : ($realisasiUnit >= $rewardTarget ? 'Memenuhi Syarat (Menunggu)' : 'Belum Tercapai'),
                'total_findings' => $totalFindings,
                'resolved_findings' => $resolvedFindings,
                'attendance_count' => $attendanceCount,
                'total_meetings' => $totalMeetings,
                'evaluation' => $evaluation,
            ];
        }

        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        return view('reports.performance', compact(
            'selectedSales',
            'salesList',
            'bulan',
            'tahun',
            'reportData'
        ));
    }

    public function exportPerformanceWord(Request $request): StreamedResponse
    {
        $currentUser = Auth::user();
        $bulan = (int) ($request->input('bulan', date('n')));
        $tahun = (int) ($request->input('tahun', date('Y')));
        $salesId = $request->input('sales_id');

        if (!$currentUser->isSupervisor()) {
            $salesId = $currentUser->id;
        }

        $selectedSales = User::findOrFail($salesId);
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $realisasiUnit = (int) SalesTransaction::valid()
            ->where('user_id', $selectedSales->id)
            ->whereBetween('tanggal_transaksi', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('jumlah_unit');

        $target = SalesTarget::where('user_id', $selectedSales->id)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        $targetUnit = $target ? $target->target_unit : 0;
        $persentase = $targetUnit > 0 ? round(($realisasiUnit / $targetUnit) * 100, 1) : 0;
        $statusKinerja = PerformanceCriterion::determineCategory($persentase);

        $rewardProgram = RewardProgram::where('is_active', true)->first();
        $rewardTarget = $rewardProgram ? $rewardProgram->target_minimal_unit : 15;
        $salesReward = SalesReward::where('user_id', $selectedSales->id)
            ->where('periode_tahun', $tahun)
            ->where('periode_bulan', $bulan)
            ->first();
        $rewardStatus = $salesReward ? $salesReward->status_label : ($realisasiUnit >= $rewardTarget ? 'Memenuhi Syarat (Menunggu Verifikasi)' : 'Belum Tercapai');

        $totalFindings = FieldFinding::where('user_id', $selectedSales->id)
            ->whereBetween('tanggal_temuan', [$startDate->toDateString(), $endDate->toDateString()])
            ->count();
        $resolvedFindings = FieldFinding::where('user_id', $selectedSales->id)
            ->whereBetween('tanggal_temuan', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status_tindak_lanjut', 'selesai')
            ->count();

        $attendanceCount = MeetingAttendance::where('user_id', $selectedSales->id)
            ->whereHas('meeting', fn($q) => $q->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]))
            ->where('status_kehadiran', 'hadir')
            ->count();
        $totalMeetings = MeetingAttendance::where('user_id', $selectedSales->id)
            ->whereHas('meeting', fn($q) => $q->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]))
            ->count();

        $evaluation = PerformanceEvaluation::where('user_id', $selectedSales->id)
            ->where('periode_tahun', $tahun)
            ->where('periode_bulan', $bulan)
            ->first();

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM Y');

        // Document Generation
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(10);

        $section = $phpWord->addSection([
            'marginTop' => 800,
            'marginBottom' => 800,
            'marginLeft' => 1000,
            'marginRight' => 1000,
        ]);

        $section->addText('SIMAKIS — SISTEM MANAJEMEN KINERJA SALES', ['bold' => true, 'size' => 14, 'color' => '0A4DF3'], ['alignment' => Jc::CENTER]);
        $section->addText('LAPORAN EVALUASI & PENILAIAN KINERJA SALES', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addText('Periode: ' . $namaBulan . ' | Sales: ' . $selectedSales->nama, ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);

        // Indicator Table
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'cellMargin' => 80, 'alignment' => JcTable::CENTER]);
        $table->addRow();
        $table->addCell(3500, ['bgColor' => 'F8FAFC'])->addText('Nama Sales / NIP', ['bold' => true]);
        $table->addCell(6500)->addText($selectedSales->nama . ' (' . $selectedSales->email . ')');

        $table->addRow();
        $table->addCell(3500, ['bgColor' => 'F8FAFC'])->addText('Cabang / Dealer', ['bold' => true]);
        $table->addCell(6500)->addText($selectedSales->cabang ?? 'Yamaha JG Purwakarta');

        $table->addRow();
        $table->addCell(3500, ['bgColor' => 'F8FAFC'])->addText('Target Penjualan Reguler', ['bold' => true]);
        $table->addCell(6500)->addText($targetUnit . ' Unit');

        $table->addRow();
        $table->addCell(3500, ['bgColor' => 'F8FAFC'])->addText('Realisasi Penjualan Valid', ['bold' => true]);
        $table->addCell(6500)->addText($realisasiUnit . ' Unit (Pencapaian: ' . $persentase . '%)', ['bold' => true, 'color' => '0A4DF3']);

        $table->addRow();
        $table->addCell(3500, ['bgColor' => 'F8FAFC'])->addText('Kategori Status Kinerja', ['bold' => true]);
        $table->addCell(6500)->addText($statusKinerja, ['bold' => true]);

        $table->addRow();
        $table->addCell(3500, ['bgColor' => 'F8FAFC'])->addText('Target Reward (15 Unit)', ['bold' => true]);
        $table->addCell(6500)->addText($rewardStatus . ' (' . $realisasiUnit . '/' . $rewardTarget . ' Unit)');

        $table->addRow();
        $table->addCell(3500, ['bgColor' => 'F8FAFC'])->addText('Temuan Lapangan Ditindaklanjuti', ['bold' => true]);
        $table->addCell(6500)->addText($resolvedFindings . ' Selesai dari ' . $totalFindings . ' Temuan Dicatat');

        $table->addRow();
        $table->addCell(3500, ['bgColor' => 'F8FAFC'])->addText('Tingkat Kehadiran Rapat', ['bold' => true]);
        $table->addCell(6500)->addText($totalMeetings > 0 ? "{$attendanceCount} Hadir dari {$totalMeetings} Rapat" : 'Data Belum Tersedia');

        $section->addTextBreak(1);
        $section->addText('Catatan Evaluasi & Rekomendasi Supervisor:', ['bold' => true, 'size' => 11]);

        $evalTable = $section->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'cellMargin' => 80, 'alignment' => JcTable::CENTER]);
        $evalTable->addRow();
        $evalTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Catatan Evaluasi', ['bold' => true]);
        $evalTable->addCell(7000)->addText($evaluation->catatan_evaluasi ?? 'Belum ada catatan evaluasi khusus untuk periode ini.');

        $evalTable->addRow();
        $evalTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Rekomendasi Peningkatan', ['bold' => true]);
        $evalTable->addCell(7000)->addText($evaluation->rekomendasi ?? 'Pertahankan konsistensi follow-up dan tingkatkan konversi prospek.');

        $evalTable->addRow();
        $evalTable->addCell(3000, ['bgColor' => 'F8FAFC'])->addText('Rencana Tindak Lanjut', ['bold' => true]);
        $evalTable->addCell(7000)->addText($evaluation->rencana_tindak_lanjut ?? 'Ikuti arahan strategi penjualan pada rapat mingguan.');

        // Signatures
        $section->addTextBreak(2);
        $sigTable = $section->addTable(['borderSize' => 0, 'alignment' => JcTable::CENTER]);
        $sigTable->addRow();
        $sigCell1 = $sigTable->addCell(5000);
        $sigCell1->addText('Sales yang Dinilai,', [], ['alignment' => Jc::CENTER]);
        $sigCell1->addTextBreak(3);
        $sigCell1->addText('( ' . $selectedSales->nama . ' )', ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::CENTER]);

        $sigCell2 = $sigTable->addCell(5000);
        $sigCell2->addText('Purwakarta, ' . now()->locale('id')->isoFormat('D MMMM Y'), [], ['alignment' => Jc::CENTER]);
        $sigCell2->addText('Supervisor Sales,', ['bold' => true], ['alignment' => Jc::CENTER]);
        $sigCell2->addTextBreak(3);
        $sigCell2->addText('( ' . $currentUser->nama . ' )', ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::CENTER]);

        $fileName = 'Laporan_Kinerja_' . str_replace(' ', '_', $selectedSales->nama) . '_' . $namaBulan . '.docx';

        ActivityLogService::log(
            'ekspor',
            'laporan',
            "Mengekspor Laporan Kinerja Tim/Sales ({$selectedSales->nama}, Periode: {$namaBulan} {$tahun})",
            null,
            ['sales' => $selectedSales->nama, 'bulan' => $namaBulan, 'tahun' => $tahun]
        );

        return response()->streamDownload(function () use ($phpWord) {
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
}
