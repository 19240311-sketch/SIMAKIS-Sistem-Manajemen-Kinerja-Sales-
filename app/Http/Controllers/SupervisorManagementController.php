<?php

namespace App\Http\Controllers;

use App\Models\SupervisorHistory;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervisorManagementController extends Controller
{
    /**
     * Display Riwayat Manajemen Supervisor (timeline & history table).
     * Accessible by Super Admin and Supervisor.
     */
    public function history()
    {
        $histories = SupervisorHistory::with(['user', 'superAdmin'])
            ->orderBy('tanggal_mulai', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        // Get currently active supervisor(s)
        $currentSupervisors = User::where('peran', User::ROLE_SUPERVISOR)
            ->where('status_aktif', true)
            ->get();

        // Candidates for supervisor replacement (active sales or users)
        $salesCandidates = User::where('peran', User::ROLE_SALES)
            ->where('status_aktif', true)
            ->orderBy('nama')
            ->get();

        return view('supervisor_management.history', compact('histories', 'currentSupervisors', 'salesCandidates'));
    }

    /**
     * Process replacement of Supervisor.
     * Exclusively accessible by Super Admin.
     */
    public function replace(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang berhak melakukan pergantian jabatan Supervisor.');
        }

        $validated = $request->validate([
            'old_supervisor_id' => 'required|exists:users,id',
            'new_supervisor_id' => 'required|exists:users,id|different:old_supervisor_id',
            'tanggal_efektif' => 'required|date',
            'status_lama' => 'required|in:sales,nonaktif',
            'alasan_pergantian' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'new_supervisor_id.different' => 'Supervisor baru tidak boleh sama dengan Supervisor lama.',
            'alasan_pergantian.required' => 'Alasan pergantian jabatan wajib diisi.',
            'status_lama.required' => 'Status untuk Supervisor lama wajib ditentukan (Sales atau Dinonaktifkan).',
        ]);

        $oldSupervisor = User::findOrFail($validated['old_supervisor_id']);
        $newSupervisor = User::findOrFail($validated['new_supervisor_id']);

        // Security check: cannot replace a Super Admin, and new candidate cannot be a Super Admin
        if ($oldSupervisor->isSuperAdmin() || $newSupervisor->isSuperAdmin()) {
            return back()->with('error', 'Pergantian jabatan tidak dapat melibatkan akun Super Admin.');
        }

        $tanggalEfektif = Carbon::parse($validated['tanggal_efektif'])->format('Y-m-d');
        $statusLama = $validated['status_lama'];

        DB::beginTransaction();
        try {
            // 1. Update existing active history record for old supervisor
            $activeHistory = SupervisorHistory::where('user_id', $oldSupervisor->id)
                ->where('status_jabatan', 'aktif')
                ->latest('tanggal_mulai')
                ->first();

            $statusAkhirLama = ($statusLama === 'nonaktif') ? 'dinonaktifkan' : 'digantikan';

            if ($activeHistory) {
                $activeHistory->update([
                    'tanggal_selesai' => $tanggalEfektif,
                    'status_jabatan' => $statusAkhirLama,
                    'alasan_pergantian' => $validated['alasan_pergantian'],
                    'catatan' => $validated['catatan'] ? ($activeHistory->catatan . "\n[Pergantian]: " . $validated['catatan']) : $activeHistory->catatan,
                ]);
            } else {
                // If no active history record existed, create an archive record for old supervisor
                SupervisorHistory::create([
                    'user_id' => $oldSupervisor->id,
                    'super_admin_id' => Auth::id(),
                    'tanggal_mulai' => $oldSupervisor->created_at?->format('Y-m-d') ?? '2026-01-01',
                    'tanggal_selesai' => $tanggalEfektif,
                    'status_jabatan' => $statusAkhirLama,
                    'alasan_pergantian' => $validated['alasan_pergantian'],
                    'catatan' => $validated['catatan'],
                ]);
            }

            // 2. Transition old supervisor account
            $oldBefore = ['peran' => $oldSupervisor->peran, 'status_aktif' => $oldSupervisor->status_aktif];
            if ($statusLama === 'nonaktif') {
                $oldSupervisor->status_aktif = false;
            } else {
                $oldSupervisor->peran = User::ROLE_SALES;
            }
            $oldSupervisor->save();
            $oldAfter = ['peran' => $oldSupervisor->peran, 'status_aktif' => $oldSupervisor->status_aktif];

            // 3. Promote new supervisor account
            $newBefore = ['peran' => $newSupervisor->peran, 'status_aktif' => $newSupervisor->status_aktif];
            $newSupervisor->peran = User::ROLE_SUPERVISOR;
            $newSupervisor->status_aktif = true;
            $newSupervisor->save();
            $newAfter = ['peran' => $newSupervisor->peran, 'status_aktif' => $newSupervisor->status_aktif];

            // 4. Create new history record for new supervisor
            SupervisorHistory::create([
                'user_id' => $newSupervisor->id,
                'super_admin_id' => Auth::id(),
                'tanggal_mulai' => $tanggalEfektif,
                'status_jabatan' => 'aktif',
                'alasan_pergantian' => 'Penunjukan sebagai Supervisor baru: ' . $validated['alasan_pergantian'],
                'catatan' => $validated['catatan'],
            ]);

            // 5. System Activity Logs
            ActivityLogService::log(
                activityType: 'ganti_jabatan',
                module: 'supervisor',
                description: "Super Admin melakukan pergantian Supervisor: {$newSupervisor->nama} menggantikan {$oldSupervisor->nama}. Status Supervisor lama: " . ($statusLama === 'nonaktif' ? 'Dinonaktifkan' : 'Ditugaskan sebagai Sales') . ".",
                record: $newSupervisor,
                beforeData: ['old_supervisor' => $oldBefore, 'new_supervisor' => $newBefore],
                afterData: ['old_supervisor' => $oldAfter, 'new_supervisor' => $newAfter, 'effective_date' => $tanggalEfektif]
            );

            DB::commit();

            return redirect()->route('supervisor.history')->with('success', "Pergantian Supervisor berhasil diproses. {$newSupervisor->nama} kini aktif sebagai Supervisor.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pergantian jabatan: ' . $e->getMessage())->withInput();
        }
    }
}

