<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Supervisor (Sebelumnya Admin)
        User::updateOrCreate(
            ['email' => 'supervisor@simakis.com'],
            [
                'nama' => 'Supervisor Dealer Utama',
                'no_hp' => '081298765432',
                'cabang' => 'Yamaha JG Purwakarta',
                'password' => Hash::make('password'),
                'peran' => 'supervisor',
                'status_aktif' => true,
            ]
        );

        // Sales 1
        User::updateOrCreate(
            ['email' => 'sales1@simakis.com'],
            [
                'nama' => 'Budi Santoso',
                'no_hp' => '081388776655',
                'cabang' => 'Yamaha JG Purwakarta',
                'password' => Hash::make('password'),
                'peran' => 'sales',
                'status_aktif' => true,
            ]
        );

        // Sales 2
        User::updateOrCreate(
            ['email' => 'sales2@simakis.com'],
            [
                'nama' => 'Siti Rahmawati',
                'no_hp' => '085712345678',
                'cabang' => 'Yamaha JG Purwakarta',
                'password' => Hash::make('password'),
                'peran' => 'sales',
                'status_aktif' => true,
            ]
        );
    }
}
