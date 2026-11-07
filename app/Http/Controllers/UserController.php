<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $currentUser = Auth::user();

        $users = User::withCount(['prospects', 'salesTransactions'])
            ->orderByRaw("FIELD(peran, 'super_admin', 'supervisor', 'sales')")
            ->orderBy('nama')
            ->get();

        // Candidates for supervisor replacement modal
        $salesCandidates = User::where('peran', User::ROLE_SALES)
            ->where('status_aktif', true)
            ->orderBy('nama')
            ->get();

        $currentSupervisor = User::where('peran', User::ROLE_SUPERVISOR)
            ->where('status_aktif', true)
            ->first();

        return view('users.index', compact('users', 'salesCandidates', 'currentSupervisor', 'currentUser'));
    }

    public function store(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();

        // Allowed roles depending on who is creating
        $allowedRoles = ['supervisor', 'sales'];
        if ($currentUser->isSuperAdmin()) {
            $allowedRoles[] = 'super_admin';
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'cabang' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ], [
            'role.in' => 'Role yang dipilih tidak valid atau Anda tidak memiliki hak memberikan role tersebut.',
        ]);

        $newUser = User::create([
            'nama' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'peran' => $validated['role'],
            'cabang' => $validated['cabang'] ?? 'Yamaha JG Purwakarta',
            'no_hp' => $validated['no_hp'] ?? null,
            'status_aktif' => true,
        ]);

        ActivityLogService::log(
            activityType: 'tambah',
            module: 'pengguna',
            description: "Menambahkan pengguna baru: {$newUser->nama} dengan role {$newUser->role_label}.",
            record: $newUser,
            afterData: [
                'nama' => $newUser->nama,
                'email' => $newUser->email,
                'peran' => $newUser->peran,
                'cabang' => $newUser->cabang,
                'status_aktif' => true,
            ]
        );

        return redirect()->route('users.index')
            ->with('success', "Pengguna {$newUser->nama} ({$newUser->role_label}) berhasil didaftarkan!");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        // Rule: Non-super_admin cannot edit a super_admin
        if ($user->isSuperAdmin() && !$currentUser->isSuperAdmin()) {
            abort(403, 'Anda tidak memiliki hak untuk mengelola akun Super Admin.');
        }

        $allowedRoles = ['supervisor', 'sales'];
        if ($currentUser->isSuperAdmin()) {
            $allowedRoles[] = 'super_admin';
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'is_active' => ['required', 'boolean'],
            'cabang' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);

        $newActive = (bool) $validated['is_active'];
        $newRole = $validated['role'];

        // Protection 1: User cannot self-promote to super_admin
        if ($currentUser->id === $user->id && !$currentUser->isSuperAdmin() && $newRole === User::ROLE_SUPER_ADMIN) {
            return back()->with('error', 'Anda tidak dapat menaikkan hak akses diri sendiri menjadi Super Admin.');
        }

        // Protection 2: Minimal 1 active Super Admin must always exist
        if ($user->isSuperAdmin() && (!$newActive || $newRole !== User::ROLE_SUPER_ADMIN)) {
            $activeSuperAdminsCount = User::where('peran', User::ROLE_SUPER_ADMIN)
                ->where('status_aktif', true)
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeSuperAdminsCount < 1) {
                return back()->with('error', 'Sistem harus selalu memiliki minimal satu akun Super Admin aktif. Operasi dibatalkan.');
            }
        }

        $beforeData = [
            'nama' => $user->nama,
            'email' => $user->email,
            'peran' => $user->peran,
            'status_aktif' => $user->status_aktif,
            'cabang' => $user->cabang,
            'no_hp' => $user->no_hp,
        ];

        $updateData = [
            'nama' => $validated['name'],
            'email' => $validated['email'],
            'peran' => $newRole,
            'status_aktif' => $newActive,
            'cabang' => $validated['cabang'] ?? $user->cabang,
            'no_hp' => $validated['no_hp'] ?? $user->no_hp,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        $afterData = [
            'nama' => $user->nama,
            'email' => $user->email,
            'peran' => $user->peran,
            'status_aktif' => $user->status_aktif,
            'cabang' => $user->cabang,
            'no_hp' => $user->no_hp,
        ];

        ActivityLogService::log(
            activityType: 'ubah',
            module: 'pengguna',
            description: "Memperbarui data pengguna: {$user->nama} (Peran: {$user->role_label}, Status: " . ($user->status_aktif ? 'Aktif' : 'Nonaktif') . ").",
            record: $user,
            beforeData: $beforeData,
            afterData: $afterData
        );

        return redirect()->route('users.index')
            ->with('success', "Data pengguna {$user->nama} berhasil diperbarui!");
    }

    /**
     * Safe deletion of user accounts.
     * Prevents deletion of accounts with transaction or critical operational history.
     */
    public function destroy(User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang berhak menghapus akun pengguna.');
        }

        // Cannot delete self
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Cannot delete last super admin
        if ($user->isSuperAdmin()) {
            $otherSuperAdmins = User::where('peran', User::ROLE_SUPER_ADMIN)->where('id', '!=', $user->id)->count();
            if ($otherSuperAdmins < 1) {
                return back()->with('error', 'Tidak dapat menghapus satu-satunya akun Super Admin dalam sistem.');
            }
        }

        // Critical history check: transactions, prospects, activities
        $transactionCount = $user->salesTransactions()->count();
        $prospectCount = $user->prospects()->count();
        $activityCount = $user->prospectActivities()->count();

        if ($transactionCount > 0 || $prospectCount > 0 || $activityCount > 0) {
            return back()->with('error', "Akun {$user->nama} tidak dapat dihapus karena memiliki riwayat operasional ({$transactionCount} transaksi, {$prospectCount} prospek). Silakan nonaktifkan akun ini jika sudah tidak aktif.");
        }

        $deletedName = $user->nama;
        $deletedRole = $user->role_label;

        ActivityLogService::log(
            activityType: 'hapus',
            module: 'pengguna',
            description: "Menghapus akun pengguna: {$deletedName} ({$deletedRole}).",
            record: null,
            beforeData: [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'peran' => $user->peran,
            ]
        );

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Akun {$deletedName} berhasil dihapus dari sistem.");
    }
}
