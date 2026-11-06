<?php

namespace Database\Seeders;

use App\Models\SupervisorHistory;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminAndHistorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or ensure Super Admin account
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@simakis.com'],
            [
                'nama' => 'Super Admin SIMAKIS',
                'no_hp' => '081122334455',
                'cabang' => 'Yamaha JG Purwakarta (Head Office)',
                'password' => Hash::make('password'),
                'peran' => User::ROLE_SUPER_ADMIN,
                'status_aktif' => true,
            ]
        );

        // 2. Ensure existing supervisor has history
        $supervisor = User::where('peran', User::ROLE_SUPERVISOR)->first();
        if ($supervisor) {
            $existingHistory = SupervisorHistory::where('user_id', $supervisor->id)
                ->where('status_jabatan', 'aktif')
                ->first();

            if (!$existingHistory) {
                SupervisorHistory::create([
                    'user_id' => $supervisor->id,
                    'super_admin_id' => $superAdmin->id,
                    'tanggal_mulai' => '2026-01-01',
                    'status_jabatan' => 'aktif',
                    'alasan_pergantian' => 'Penetapan awal Supervisor Operasional SIMAKIS',
                    'catatan' => 'Supervisor aktif memimpin tim sales Yamaha JG Purwakarta.',
                ]);
            }
        }

        // 3. Log the system seed initialization
        ActivityLogService::log(
            activityType: 'tambah',
            module: 'pengguna',
            description: 'Inisialisasi akun Super Admin dan pencatatan masa jabatan Supervisor awal.',
            record: $superAdmin,
            overrideUser: $superAdmin
        );
    }
}

