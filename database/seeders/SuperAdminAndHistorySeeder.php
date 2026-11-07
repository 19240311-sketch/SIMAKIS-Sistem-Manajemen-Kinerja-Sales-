<?php

namespace Database\Seeders;

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

        // 2. Log the system seed initialization
        ActivityLogService::log(
            activityType: 'tambah',
            module: 'pengguna',
            description: 'Inisialisasi akun Super Admin SIMAKIS.',
            record: $superAdmin,
            overrideUser: $superAdmin
        );
    }
}
