<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Prospect;
use App\Models\ProspectActivity;
use App\Models\SalesTarget;
use App\Models\SalesTransaction;
use App\Models\SupervisorHandover;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupervisorHandoverController extends Controller
{
    /**
     * Display listing of Handover documents.
     * Super Admin sees all.
     * Supervisor sees documents related to their tenure.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = SupervisorHandover::with(['supervisorLama', 'supervisorBaru', 'superAdmin'])
            ->latest('tanggal_serah_terima');

        if (!$user->isSuperAdmin()) {
            // Supervisor only sees handovers where they are involved
            $query->where(function ($q) use ($user) {
                $q->where('supervisor_lama_id', $user->id)
                  ->orWhere('supervisor_baru_id', $user->id);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $handovers = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => SupervisorHandover::count(),
            'menunggu' => SupervisorHandover::where('status', 'menunggu_serah_terima')->count(),
            'dalam_proses' => SupervisorHandover::where('status', 'dalam_proses')->count(),
            'selesai' => SupervisorHandover::where('status', 'selesai')->count(),
        ];

        return view('supervisor_handovers.index', compact('handovers', 'stats'));
    }

    /**
     * Show form to create new Handover document (Super Admin only).
     */
    public function create()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang berhak menyusun dokumen Serah Terima Jabatan.');
        }

        // Current and former supervisors
        $supervisors = User::whereIn('peran', [User::ROLE_SUPERVISOR, User::ROLE_SALES])
            ->orderBy('nama')
            ->get();

        $activeSupervisor = User::where('peran', User::ROLE_SUPERVISOR)
            ->where('status_aktif', true)
            ->first();

        // Generate automatic operational snapshot data for review
        $bulanSekarang = now()->month;
        $tahunSekarang = now()->year;

        // Sales summary
        $totalTransaksiBulanIni = SalesTransaction::whereMonth('tanggal_transaksi', $bulanSekarang)
            ->whereYear('tanggal_transaksi', $tahunSekarang)
            ->count();
        $totalNominalBulanIni = SalesTransaction::whereMonth('tanggal_transaksi', $bulanSekarang)
            ->whereYear('tanggal_transaksi', $tahunSekarang)
            ->sum('total_harga');

        // Target sales
        $salesList = User::where('peran', User::ROLE_SALES)->where('status_aktif', true)->get();
        $pencapaianTarget = [];
        foreach ($salesList as $sales) {
            $target = SalesTarget::where('user_id', $sales->id)
                ->where('bulan', $bulanSekarang)
                ->where('tahun', $tahunSekarang)
                ->first();

            $realisasi = SalesTransaction::where('user_id', $sales->id)
                ->whereMonth('tanggal_transaksi', $bulanSekarang)
                ->whereYear('tanggal_transaksi', $tahunSekarang)
                ->count();

            $targetUnit = $target ? $target->target_unit : 0;
            $persentase = $targetUnit > 0 ? round(($realisasi / $targetUnit) * 100, 1) : 0;

            $pencapaianTarget[] = [
                'sales_nama' => $sales->nama,
                'target_unit' => $targetUnit,
                'realisasi_unit' => $realisasi,
                'persentase' => $persentase,
            ];
        }

        // Active prospects
        $prospekAktif = Prospect::with(['user', 'motorcycle', 'status'])
            ->whereHas('status', function ($q) {
                $q->whereNotIn('nama_status', ['Deal', 'Batal', 'Closed', 'Selesai']);
            })
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($p) {
                return [
                    'nama_konsumen' => $p->nama_konsumen,
                    'no_hp' => $p->nomor_telepon,
                    'motor' => $p->motorcycle?->nama_model ?? 'Umum',
                    'sales' => $p->user?->nama ?? 'Tanpa Sales',
                    'status' => $p->status?->nama_status ?? 'Follow Up',
                ];
            })->toArray();

        // Pending follow-ups
        $followupPending = ProspectActivity::with(['prospect', 'user'])
            ->where('waktu_aktivitas', '>=', now())
            ->orderBy('waktu_aktivitas', 'asc')
            ->take(10)
            ->get()
            ->map(function ($act) {
                return [
                    'konsumen' => $act->prospect?->nama_konsumen ?? '-',
                    'tipe' => ucfirst($act->jenis_aktivitas ?? 'Aktivitas'),
                    'tanggal' => $act->waktu_aktivitas ? Carbon::parse($act->waktu_aktivitas)->format('d M Y H:i') : '-',
                    'sales' => $act->user?->nama ?? '-',
                    'catatan' => Str::limit($act->catatan ?? '-', 40),
                ];
            })->toArray();

        // Upcoming meetings
        $rapatMendatang = Meeting::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->take(5)
            ->get()
            ->map(function ($m) {
                return [
                    'judul' => $m->judul_rapat,
                    'tanggal' => Carbon::parse($m->tanggal)->format('d M Y'),
                    'jam' => substr($m->waktu_mulai, 0, 5) . ' - ' . substr($m->waktu_selesai, 0, 5),
                    'tipe' => ucfirst($m->metode ?? 'Offline'),
                    'tempat' => $m->nama_tempat ?? $m->link_zoom ?? 'Dealer JG Purwakarta',
                ];
            })->toArray();

        $snapshotPreview = [
            'ringkasan_penjualan' => [
                'periode' => now()->translatedFormat('F Y'),
                'total_unit' => $totalTransaksiBulanIni,
                'total_omzet' => $totalNominalBulanIni,
                'jumlah_sales_aktif' => $salesList->count(),
            ],
            'pencapaian_target' => $pencapaianTarget,
            'daftar_prospek_aktif' => $prospekAktif,
            'daftar_followup_pending' => $followupPending,
            'jadwal_rapat_mendatang' => $rapatMendatang,
        ];

        return view('supervisor_handovers.create', compact('supervisors', 'activeSupervisor', 'snapshotPreview'));
    }

    /**
     * Store new Handover document.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang berhak membuat dokumen Serah Terima Jabatan.');
        }

        $validated = $request->validate([
            'supervisor_lama_id' => 'required|exists:users,id',
            'supervisor_baru_id' => 'required|exists:users,id|different:supervisor_lama_id',
            'tanggal_serah_terima' => 'required|date',
            'alasan_pergantian' => 'required|string|max:500',
            'catatan_supervisor_lama' => 'nullable|string',
            'catatan_super_admin' => 'nullable|string',
        ], [
            'supervisor_baru_id.different' => 'Supervisor baru tidak boleh sama dengan Supervisor lama.',
            'alasan_pergantian.required' => 'Alasan serah terima jabatan wajib diisi.',
        ]);

        // Auto-generate handover document number
        $nomorSerahTerima = 'STJ-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

        // Compile comprehensive live snapshot
        $bulanSekarang = now()->month;
        $tahunSekarang = now()->year;

        $totalTransaksiBulanIni = SalesTransaction::whereMonth('tanggal_transaksi', $bulanSekarang)
            ->whereYear('tanggal_transaksi', $tahunSekarang)
            ->count();
        $totalNominalBulanIni = SalesTransaction::whereMonth('tanggal_transaksi', $bulanSekarang)
            ->whereYear('tanggal_transaksi', $tahunSekarang)
            ->sum('total_harga');

        $salesList = User::where('peran', User::ROLE_SALES)->where('status_aktif', true)->get();
        $pencapaianTarget = [];
        foreach ($salesList as $sales) {
            $target = SalesTarget::where('user_id', $sales->id)
                ->where('bulan', $bulanSekarang)
                ->where('tahun', $tahunSekarang)
                ->first();

            $realisasi = SalesTransaction::where('user_id', $sales->id)
                ->whereMonth('tanggal_transaksi', $bulanSekarang)
                ->whereYear('tanggal_transaksi', $tahunSekarang)
                ->count();

            $targetUnit = $target ? $target->target_unit : 0;
            $persentase = $targetUnit > 0 ? round(($realisasi / $targetUnit) * 100, 1) : 0;

            $pencapaianTarget[] = [
                'sales_nama' => $sales->nama,
                'target_unit' => $targetUnit,
                'realisasi_unit' => $realisasi,
                'persentase' => $persentase,
            ];
        }

        $prospekAktif = Prospect::with(['user', 'motorcycle', 'status'])
            ->whereHas('status', function ($q) {
                $q->whereNotIn('nama_status', ['Deal', 'Batal', 'Closed', 'Selesai']);
            })
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($p) {
                return [
                    'nama_konsumen' => $p->nama_konsumen,
                    'no_hp' => $p->nomor_telepon,
                    'motor' => $p->motorcycle?->nama_model ?? 'Umum',
                    'sales' => $p->user?->nama ?? 'Tanpa Sales',
                    'status' => $p->status?->nama_status ?? 'Follow Up',
                ];
            })->toArray();

        $followupPending = ProspectActivity::with(['prospect', 'user'])
            ->where('waktu_aktivitas', '>=', now())
            ->orderBy('waktu_aktivitas', 'asc')
            ->take(15)
            ->get()
            ->map(function ($act) {
                return [
                    'konsumen' => $act->prospect?->nama_konsumen ?? '-',
                    'tipe' => ucfirst($act->jenis_aktivitas ?? 'Aktivitas'),
                    'tanggal' => $act->waktu_aktivitas ? Carbon::parse($act->waktu_aktivitas)->format('d M Y H:i') : '-',
                    'sales' => $act->user?->nama ?? '-',
                    'catatan' => Str::limit($act->catatan ?? '-', 60),
                ];
            })->toArray();

        $rapatMendatang = Meeting::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->take(10)
            ->get()
            ->map(function ($m) {
                return [
                    'judul' => $m->judul_rapat,
                    'tanggal' => Carbon::parse($m->tanggal)->format('d M Y'),
                    'jam' => substr($m->waktu_mulai, 0, 5) . ' - ' . substr($m->waktu_selesai, 0, 5),
                    'tipe' => ucfirst($m->metode ?? 'Offline'),
                    'tempat' => $m->nama_tempat ?? $m->link_zoom ?? 'Dealer JG Purwakarta',
                ];
            })->toArray();

        $handover = SupervisorHandover::create([
            'nomor_serah_terima' => $nomorSerahTerima,
            'supervisor_lama_id' => $validated['supervisor_lama_id'],
            'supervisor_baru_id' => $validated['supervisor_baru_id'],
            'super_admin_id' => Auth::id(),
            'tanggal_serah_terima' => $validated['tanggal_serah_terima'],
            'status' => 'menunggu_serah_terima',
            'alasan_pergantian' => $validated['alasan_pergantian'],
            'ringkasan_penjualan' => [
                'periode' => now()->translatedFormat('F Y'),
                'total_unit' => $totalTransaksiBulanIni,
                'total_omzet' => $totalNominalBulanIni,
                'jumlah_sales_aktif' => $salesList->count(),
            ],
            'pencapaian_target' => $pencapaianTarget,
            'daftar_prospek_aktif' => $prospekAktif,
            'daftar_followup_pending' => $followupPending,
            'jadwal_rapat_mendatang' => $rapatMendatang,
            'catatan_supervisor_lama' => $validated['catatan_supervisor_lama'],
            'catatan_super_admin' => $validated['catatan_super_admin'],
        ]);

        ActivityLogService::log(
            activityType: 'tambah',
            module: 'serah_terima',
            description: "Super Admin menyusun dokumen Serah Terima Jabatan No. {$nomorSerahTerima}.",
            record: $handover
        );

        return redirect()->route('supervisor.handovers.show', $handover->id)
            ->with('success', "Dokumen Serah Terima Jabatan {$nomorSerahTerima} berhasil dibuat.");
    }

    /**
     * Display structured detail view of Handover document.
     */
    public function show($id)
    {
        $handover = SupervisorHandover::with(['supervisorLama', 'supervisorBaru', 'superAdmin'])
            ->findOrFail($id);

        $user = Auth::user();

        // Access check
        if (!$user->isSuperAdmin() && $user->id !== $handover->supervisor_lama_id && $user->id !== $handover->supervisor_baru_id) {
            abort(403, 'Akses ditolak. Anda tidak memiliki kewenangan melihat dokumen serah terima ini.');
        }

        return view('supervisor_handovers.show', compact('handover'));
    }

    /**
     * Update Handover status workflow.
     */
    public function updateStatus(Request $request, $id)
    {
        $handover = SupervisorHandover::findOrFail($id);
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $user->id !== $handover->supervisor_baru_id && $user->id !== $handover->supervisor_lama_id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'status' => 'required|in:menunggu_serah_terima,dalam_proses,selesai',
            'catatan_tambahan' => 'nullable|string|max:500',
        ]);

        $statusSebelum = $handover->status;
        $handover->status = $validated['status'];

        if ($validated['status'] === 'selesai' && !$handover->diselesaikan_pada) {
            $handover->diselesaikan_pada = now();
        }

        if (!empty($validated['catatan_tambahan'])) {
            $penulis = $user->nama . ' (' . $user->role_label . ')';
            $handover->catatan_super_admin = ($handover->catatan_super_admin ? $handover->catatan_super_admin . "\n\n" : '')
                . "[Update Status oleh {$penulis}]: " . $validated['catatan_tambahan'];
        }

        $handover->save();

        ActivityLogService::log(
            activityType: 'status_change',
            module: 'serah_terima',
            description: "Status Dokumen Serah Terima Jabatan {$handover->nomor_serah_terima} diperbarui dari '{$statusSebelum}' menjadi '{$handover->status}'.",
            record: $handover,
            beforeData: ['status' => $statusSebelum],
            afterData: ['status' => $handover->status]
        );

        return back()->with('success', "Status dokumen serah terima berhasil diperbarui menjadi {$handover->status_badge['label']}.");
    }
}

