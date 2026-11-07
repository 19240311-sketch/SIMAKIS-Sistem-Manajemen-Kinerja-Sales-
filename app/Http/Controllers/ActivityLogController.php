<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display System Activity Logs.
     * Enforces strict role-based scoping:
     * - Super Admin: views all logs across all modules.
     * - Supervisor: views operational sales logs (sales team activities).
     * - Sales: views strictly their own activity logs.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ActivityLog::with('user')->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        // 1. Role-based scoping
        if ($user->isSupervisor() || $user->isSuperAdmin()) {
            // Super Admin and Supervisor have complete, unrestricted access to ALL system logs
            // Allows full transparency to oversee system and user actions
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            if ($request->filled('role')) {
                $query->where('user_peran', $request->role);
            }
        } else {
            // Sales sees exclusively their own logs
            $query->where('user_id', $user->id);
        }

        // 2. Module filter
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        // 3. Activity Type filter
        if ($request->filled('jenis_aktivitas')) {
            $query->where('jenis_aktivitas', $request->jenis_aktivitas);
        }

        // 4. Date Range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 5. Keyword Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Summary counts (calculated based on allowed scope)
        $scopedBase = ActivityLog::query();
        if (!$user->isSupervisor() && !$user->isSuperAdmin()) {
            // Only Sales users are scoped to their own logs
            $scopedBase->where('user_id', $user->id);
        }

        $stats = [
            'total' => (clone $scopedBase)->count(),
            'hari_ini' => (clone $scopedBase)->whereDate('created_at', Carbon::today())->count(),
            'auth' => (clone $scopedBase)->where('modul', 'autentikasi')->count(),
            'operasional' => (clone $scopedBase)->whereIn('modul', ['prospek', 'transaksi', 'target', 'reward', 'pelatihan', 'tes_mingguan', 'rapat'])->count(),
        ];

        $logs = $query->paginate(20)->withQueryString();

        // Dropdown data for filters
        $usersList = [];
        if ($user->isSupervisor() || $user->isSuperAdmin()) {
            // Super Admin and Supervisor can filter all registered users
            $usersList = User::orderBy('nama')->get();
        }

        $modulesList = [
            'autentikasi' => 'Autentikasi (Login/Logout)',
            'prospek' => 'Prospek & Konsumen',
            'transaksi' => 'Transaksi Penjualan',
            'target' => 'Target Sales',
            'reward' => 'Reward & Apresiasi',
            'pengguna' => 'Manajemen Pengguna',
            'supervisor' => 'Pergantian Supervisor',
            'serah_terima' => 'Serah Terima Jabatan',
            'pelatihan' => 'Pelatihan & Training',
            'tes_mingguan' => 'Tes Mingguan',
            'rapat' => 'Rapat Mingguan',
            'laporan' => 'Laporan & Ekspor',
        ];

        $activityTypes = [
            'login' => 'Login Berhasil',
            'logout' => 'Logout',
            'login_failed' => 'Login Gagal',
            'tambah' => 'Tambah Data',
            'ubah' => 'Ubah Data',
            'hapus' => 'Hapus Data',
            'status_change' => 'Perubahan Status',
            'ganti_jabatan' => 'Pergantian Jabatan',
            'ekspor' => 'Ekspor Dokumen',
        ];

        return view('activity_logs.index', compact(
            'logs',
            'stats',
            'usersList',
            'modulesList',
            'activityTypes'
        ));
    }

    /**
     * Show detail of an activity log (including before & after data).
     */
    public function show($id)
    {
        $user = Auth::user();
        $log = ActivityLog::with('user')->findOrFail($id);

        // Security check: Super Admin and Supervisor have complete audit transparency
        if (!$user->isSupervisor() && !$user->isSuperAdmin()) {
            if ($log->user_id !== $user->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'log' => $log,
            ]);
        }

        return view('activity_logs.show', compact('log'));
    }
}

