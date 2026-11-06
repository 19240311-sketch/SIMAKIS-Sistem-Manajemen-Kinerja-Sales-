<?php

namespace Database\Seeders;

use App\Models\FieldFinding;
use App\Models\Meeting;
use App\Models\MeetingAttendance;
use App\Models\Motorcycle;
use App\Models\PerformanceEvaluation;
use App\Models\Prospect;
use App\Models\ProspectActivity;
use App\Models\ProspectStatus;
use App\Models\RewardProgram;
use App\Models\SalesReward;
use App\Models\SalesTarget;
use App\Models\SalesTransaction;
use App\Models\Source;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SimakisDemoSeeder extends Seeder
{
    /**
     * Seed realistic 6-month simulation data for SIMAKIS presentation.
     * All demo records are marked with is_demo = true.
     * Original data and existing accounts are safely preserved.
     */
    public function run(): void
    {
        // 1. Dapatkan akun Supervisor dan 4 akun Sales yang sudah tersedia
        $supervisor = User::where('peran', 'supervisor')->first();
        if (!$supervisor) {
            $supervisor = User::updateOrCreate(
                ['email' => 'supervisor@simakis.com'],
                [
                    'nama' => 'Kusmayadi Sofyan',
                    'no_hp' => '081298765432',
                    'cabang' => 'Yamaha JG Purwakarta',
                    'password' => Hash::make('password'),
                    'peran' => 'supervisor',
                    'status_aktif' => true,
                    'is_demo' => false,
                ]
            );
        }

        // Akun Sales Asli (is_demo = false)
        $salesBudi = User::where('email', 'sales1@simakis.com')->first();
        if (!$salesBudi) {
            $salesBudi = User::updateOrCreate(
                ['email' => 'sales1@simakis.com'],
                [
                    'nama' => 'Budi Santoso',
                    'kode_sales' => 'SL-001',
                    'no_hp' => '081388776655',
                    'cabang' => 'Yamaha JG Purwakarta',
                    'peran' => 'sales',
                    'status_aktif' => true,
                    'is_demo' => false,
                ]
            );
        }

        $salesSiti = User::where('email', 'sales2@simakis.com')->first();
        if (!$salesSiti) {
            $salesSiti = User::updateOrCreate(
                ['email' => 'sales2@simakis.com'],
                [
                    'nama' => 'Siti Rahmawati',
                    'kode_sales' => 'SL-002',
                    'no_hp' => '085712345678',
                    'cabang' => 'Yamaha JG Purwakarta',
                    'peran' => 'sales',
                    'status_aktif' => true,
                    'is_demo' => false,
                ]
            );
        }

        // Akun Sales Demo yang sudah ada di database (is_demo = true)
        $salesDoni = User::where('email', 'sales3@simakis.com')->first();
        if (!$salesDoni) {
            $salesDoni = User::updateOrCreate(
                ['email' => 'sales3@simakis.com'],
                [
                    'nama' => 'Doni Kusuma',
                    'kode_sales' => 'SL-003',
                    'no_hp' => '081233445566',
                    'cabang' => 'Yamaha JG Purwakarta',
                    'password' => Hash::make('password'),
                    'peran' => 'sales',
                    'status_aktif' => true,
                    'is_demo' => true,
                ]
            );
        }

        $salesRina = User::where('email', 'sales4@simakis.com')->first();
        if (!$salesRina) {
            $salesRina = User::updateOrCreate(
                ['email' => 'sales4@simakis.com'],
                [
                    'nama' => 'Rina Anggraini',
                    'kode_sales' => 'SL-004',
                    'no_hp' => '081988776655',
                    'cabang' => 'Yamaha JG Purwakarta',
                    'password' => Hash::make('password'),
                    'peran' => 'sales',
                    'status_aktif' => true,
                    'is_demo' => true,
                ]
            );
        }

        $salesList = [
            'siti' => $salesSiti,
            'budi' => $salesBudi,
            'doni' => $salesDoni,
            'rina' => $salesRina,
        ];

        // 2. BERSIHKAN DATA DEMO LAMA SECARA AMAN AGAR TIDAK GANDA (Idempotent)
        // Data operasional asli (is_demo = false) sama sekali tidak disentuh!
        ProspectActivity::where('is_demo', true)->delete();
        SalesTransaction::where('is_demo', true)->forceDelete();
        Prospect::where('is_demo', true)->forceDelete();
        SalesTarget::where('is_demo', true)->delete();
        SalesReward::where('is_demo', true)->delete();
        RewardProgram::where('is_demo', true)->delete();
        FieldFinding::where('is_demo', true)->delete();
        MeetingAttendance::where('is_demo', true)->delete();
        Meeting::where('is_demo', true)->delete();
        PerformanceEvaluation::where('is_demo', true)->delete();

        // 3. REFERENSI MASTER DATA
        $motorcycles = Motorcycle::where('status_aktif', true)->get();
        if ($motorcycles->isEmpty()) {
            $motorcycles = Motorcycle::all();
        }

        $sources = Source::where('status_aktif', true)->get();
        if ($sources->isEmpty()) {
            $sources = Source::all();
        }

        $statusDeal = ProspectStatus::where('kode', 'deal')->first();
        $statusHot = ProspectStatus::where('kode', 'hot')->first();
        $statusFollowUp = ProspectStatus::where('kode', 'follow_up')->first();
        $statusBaru = ProspectStatus::where('kode', 'baru')->first();
        $statusBatal = ProspectStatus::where('kode', 'batal')->first();

        // 4. PROGRAM REWARD SIMULASI (15 Unit, Bonus Rp 1.500.000)
        $rewardProgram = RewardProgram::create([
            'nama_program' => 'Program Reward Penjualan 15 Unit Bulanan',
            'deskripsi' => 'Apresiasi bonus pencapaian target penjualan minimal 15 unit sepeda motor Yamaha dalam satu bulan berjalan (Simulasi Presentasi).',
            'target_minimal_unit' => 15,
            'jenis_hadiah' => 'bonus_insentif',
            'nominal_hadiah' => 1500000,
            'ketentuan' => 'Hanya berlaku untuk transaksi unit dengan status valid terverifikasi.',
            'is_active' => true,
            'dibuat_oleh' => $supervisor->id,
            'is_demo' => true,
        ]);

        // 5. DISTRIBUSI 6 BULAN (Mei 2026 s/d Oktober 2026)
        // Target: 15 unit/bulan/sales.
        // Variasi prospek: 40-150 prospek per sales per bulan.
        // Rasio konversi: 8% - 15%.
        $monthlyPlans = [
            5 => [ // Mei 2026
                'siti' => ['prospects' => 125, 'deals' => 15], // 15 unit (Capai Target!)
                'budi' => ['prospects' => 105, 'deals' => 13], // 13 unit (Belum capai)
                'doni' => ['prospects' => 70,  'deals' => 8],  // 8 unit
                'rina' => ['prospects' => 55,  'deals' => 6],  // 6 unit
            ],
            6 => [ // Juni 2026
                'siti' => ['prospects' => 100, 'deals' => 12], // 12 unit
                'budi' => ['prospects' => 115, 'deals' => 14], // 14 unit (Hampir capai 93%)
                'doni' => ['prospects' => 85,  'deals' => 11], // 11 unit
                'rina' => ['prospects' => 75,  'deals' => 9],  // 9 unit
            ],
            7 => [ // Juli 2026
                'siti' => ['prospects' => 135, 'deals' => 17], // 17 unit (Capai Target!)
                'budi' => ['prospects' => 140, 'deals' => 15], // 15 unit (Capai Target!)
                'doni' => ['prospects' => 80,  'deals' => 9],  // 9 unit
                'rina' => ['prospects' => 85,  'deals' => 10], // 10 unit
            ],
            8 => [ // Agustus 2026
                'siti' => ['prospects' => 110, 'deals' => 14], // 14 unit (Hampir capai)
                'budi' => ['prospects' => 85,  'deals' => 11], // 11 unit
                'doni' => ['prospects' => 110, 'deals' => 15], // 15 unit (Doni Capai Target!)
                'rina' => ['prospects' => 70,  'deals' => 9],  // 9 unit
            ],
            9 => [ // September 2026
                'siti' => ['prospects' => 120, 'deals' => 15], // 15 unit (Capai Target!)
                'budi' => ['prospects' => 130, 'deals' => 16], // 16 unit (Budi Juara 1 Bulan Ini!)
                'doni' => ['prospects' => 90,  'deals' => 12], // 12 unit
                'rina' => ['prospects' => 65,  'deals' => 8],  // 8 unit
            ],
            10 => [ // Oktober 2026 (Bulan Berjalan)
                'siti' => ['prospects' => 140, 'deals' => 16], // 16 unit (Siti Juara 1 Bulan Ini!)
                'budi' => ['prospects' => 95,  'deals' => 13], // 13 unit (Hampir capai)
                'doni' => ['prospects' => 75,  'deals' => 10], // 10 unit
                'rina' => ['prospects' => 60,  'deals' => 7],  // 7 unit
            ],
        ];

        // Nama-nama konsumen realistis
        $firstNames = ['Ahmad', 'Bambang', 'Cahyo', 'Dedi', 'Eko', 'Fajar', 'Gunawan', 'Hendra', 'Indra', 'Joko',
                       'Kurniawan', 'Lukman', 'Maulana', 'Nugroho', 'Oki', 'Prasetyo', 'Rizky', 'Sigit', 'Tri', 'Wahyu',
                       'Agus', 'Bayu', 'Dimas', 'Farhan', 'Gilang', 'Hafiz', 'Irfan', 'Kevin', 'Lutfi', 'Muhammad',
                       'Anisa', 'Citra', 'Dewi', 'Endah', 'Fitri', 'Gita', 'Hani', 'Intan', 'Kartika', 'Lestari',
                       'Maya', 'Nurul', 'Putri', 'Rina', 'Sari', 'Tiara', 'Utami', 'Wulan', 'Yulia', 'Zahra'];

        $lastNames = ['Pratama', 'Santoso', 'Wijaya', 'Saputra', 'Kusuma', 'Hidayat', 'Nugraha', 'Setiawan', 'Ramadhan', 'Firmansyah',
                      'Wibowo', 'Siregar', 'Lubis', 'Pasaribu', 'Simanjuntak', 'Subekti', 'Kurnia', 'Pangestu', 'Suharto', 'Mulyadi',
                      'Anggraini', 'Permatasari', 'Handayani', 'Puspitasari', 'Rahmawati', 'Oktaviani', 'Kusumawati', 'Sucipto', 'Hakim', 'Bachtiar'];

        $subdistricts = [
            ['kelurahan' => 'Nagri Kaler', 'kecamatan' => 'Purwakarta', 'alamat' => 'Jl. Veteran No. '],
            ['kelurahan' => 'Ciseureuh', 'kecamatan' => 'Purwakarta', 'alamat' => 'Perum Ciseureuh Blok '],
            ['kelurahan' => 'Nagri Kidul', 'kecamatan' => 'Purwakarta', 'alamat' => 'Jl. Jenderal Sudirman No. '],
            ['kelurahan' => 'Sindangkasih', 'kecamatan' => 'Purwakarta', 'alamat' => 'Jl. Ipik Gandamanah No. '],
            ['kelurahan' => 'Maracang', 'kecamatan' => 'Babakancikao', 'alamat' => 'Perum Griya Asri Blok '],
            ['kelurahan' => 'Cibatu', 'kecamatan' => 'Cibatu', 'alamat' => 'Jl. Raya Cibatu No. '],
            ['kelurahan' => 'Campaka', 'kecamatan' => 'Campaka', 'alamat' => 'Desa Campaka RT 02/01 No. '],
            ['kelurahan' => 'Jatiluhur', 'kecamatan' => 'Jatiluhur', 'alamat' => 'Komp. Danau Jatiluhur No. '],
            ['kelurahan' => 'Pasawahan', 'kecamatan' => 'Pasawahan', 'alamat' => 'Jl. Terusan Kapten Halim No. '],
            ['kelurahan' => 'Bungursari', 'kecamatan' => 'Bungursari', 'alamat' => 'Kawasan Industri BIC No. '],
        ];

        $colors = ['Matte Black', 'Metallic Cyan', 'Prestige Silver', 'Neo Red', 'White Pearl', 'Magma Black', 'Racing Blue', 'Elixir Dark Silver'];
        $leasings = ['BAF (Bussan Auto Finance)', 'Adira Finance', 'Mandiri Utama Finance (MUF)', 'OTO Finance'];

        $trxGlobalCounter = 1;
        $prospectGlobalCounter = 1;

        $targetUnitPerMonth = 15;

        // Loop 6 bulan
        foreach ($monthlyPlans as $month => $salesPlans) {
            $year = 2026;
            $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
            $monthEnd = Carbon::create($year, $month, 1)->endOfMonth();

            // 1. Buat Target Bulanan 15 Unit untuk seluruh sales
            foreach ($salesList as $salesKey => $salesUser) {
                SalesTarget::create([
                    'user_id' => $salesUser->id,
                    'periode_tipe' => 'bulanan',
                    'tahun' => $year,
                    'bulan' => $month,
                    'tanggal_mulai' => $monthStart->toDateString(),
                    'tanggal_selesai' => $monthEnd->toDateString(),
                    'target_unit' => $targetUnitPerMonth,
                    'catatan' => "Target penjualan bulanan dealer Yamaha JG Purwakarta periode {$month}/{$year}",
                    'dibuat_oleh' => $supervisor->id,
                    'is_demo' => true,
                ]);
            }

            // 2. Buat Prospek, Aktivitas Follow-up, dan Transaksi untuk setiap sales
            foreach ($salesPlans as $salesKey => $plan) {
                $salesUser = $salesList[$salesKey];
                $totalProspects = $plan['prospects'];
                $dealCount = $plan['deals'];

                // Distribusi status untuk sisa prospek non-deal
                // Misal: Deal (dealCount), Hot (~20%), Follow-up (~25%), Baru (~10%), Batal (~35%)
                $remaining = $totalProspects - $dealCount;
                $hotCount = (int) round($remaining * 0.22);
                $followUpCount = (int) round($remaining * 0.26);
                $baruCount = (int) round($remaining * 0.12);
                $batalCount = max(0, $remaining - $hotCount - $followUpCount - $baruCount);

                $statusDistribution = array_merge(
                    array_fill(0, $dealCount, 'deal'),
                    array_fill(0, $hotCount, 'hot'),
                    array_fill(0, $followUpCount, 'follow_up'),
                    array_fill(0, $baruCount, 'baru'),
                    array_fill(0, $batalCount, 'batal')
                );
                shuffle($statusDistribution);

                $dealsGenerated = 0;

                for ($i = 0; $i < $totalProspects; $i++) {
                    $statusCode = $statusDistribution[$i] ?? 'follow_up';

                    // Nama konsumen unik
                    $fName = $firstNames[($i * 7 + $month * 3 + $salesUser->id * 5) % count($firstNames)];
                    $lName = $lastNames[($i * 11 + $month * 5 + $salesUser->id * 3) % count($lastNames)];
                    $custName = "{$fName} {$lName}";

                    $loc = $subdistricts[($i + $month) % count($subdistricts)];
                    $custAddress = $loc['alamat'] . (($i % 45) + 1) . ', ' . $loc['kelurahan'] . ', ' . $loc['kecamatan'] . ', Purwakarta';

                    $custPhone = '08' . sprintf('%02d', 11 + ($i % 88)) . sprintf('%04d', $month * 100 + ($i % 99)) . sprintf('%04d', $prospectGlobalCounter % 10000);

                    $motor = $motorcycles->isNotEmpty() ? $motorcycles[$i % $motorcycles->count()] : null;
                    $source = $sources->isNotEmpty() ? $sources[($i + $salesUser->id) % $sources->count()] : null;
                    $color = $colors[$i % count($colors)];

                    $isCredit = ($i % 3 !== 0); // 67% kredit, 33% cash
                    $paymentScheme = $isCredit ? 'kredit' : 'cash';
                    $leasingName = $isCredit ? $leasings[$i % count($leasings)] : null;
                    $dpAmount = $isCredit ? (int) round(($motor ? $motor->harga_otr : 30000000) * 0.15) : null;
                    $tenor = $isCredit ? (11 + (($i % 3) * 12)) : null; // 11, 23, 35 bulan

                    // Sebarkan tanggal masuk prospek secara merata di bulan tersebut (hari 1 s/d 28)
                    $dayOfEntry = min(28, max(1, (int) round(1 + ($i / $totalProspects) * 27)));
                    $prospectCreatedAt = Carbon::create($year, $month, $dayOfEntry, 8 + ($i % 9), ($i * 7) % 60, 0);

                    // Tentukan model status
                    $statusModel = match ($statusCode) {
                        'deal' => $statusDeal,
                        'hot' => $statusHot,
                        'follow_up' => $statusFollowUp,
                        'baru' => $statusBaru,
                        default => $statusBatal,
                    };

                    $dealDate = null;
                    if ($statusCode === 'deal') {
                        // Tanggal deal 1 s/d 4 hari setelah tanggal masuk, tidak melebihi akhir bulan
                        $dealDay = min(28, $dayOfEntry + (($i % 3) + 1));
                        $dealDate = Carbon::create($year, $month, $dealDay)->toDateString();
                    }

                    // Catatan prospek realistis
                    $prospectNotes = match ($statusCode) {
                        'deal' => "Konsumen berminat unit {$motor?->nama_model} warna {$color}. Pengajuan {$paymentScheme} disetujui, SPK dan tanda jadi telah selesai.",
                        'hot' => "Minat sangat tinggi untuk model {$motor?->nama_model}. Sedang membandingkan simulasi angsuran DP promo.",
                        'follow_up' => "Konsumen meminta rincian brosur dan skema kredit untuk {$motor?->nama_model}. Menunggu konfirmasi keluarga.",
                        'baru' => "Prospek baru dari {$source?->nama_sumber}. Menanyakan estimasi ketersediaan unit dan warna {$color}.",
                        'batal' => "Batal karena " . (($i % 2 === 0) ? "pengajuan leasing belum memenuhi syarat analis." : "anggaran dialihkan untuk kebutuhan renovasi rumah."),
                    };

                    // Insert Prospect
                    $prospect = Prospect::create([
                        'user_id' => $salesUser->id,
                        'sepeda_motor_id' => $motor?->id,
                        'sumber_prospek_id' => $source?->id,
                        'status_prospek_id' => $statusModel->id,
                        'nama_konsumen' => $custName,
                        'nomor_telepon' => $custPhone,
                        'alamat' => $custAddress,
                        'kelurahan' => $loc['kelurahan'],
                        'kecamatan' => $loc['kecamatan'],
                        'kota' => 'Purwakarta',
                        'provinsi' => 'Jawa Barat',
                        'kode_pos' => '41115',
                        'warna_motor_diminati' => $color,
                        'harga_otr' => $motor?->harga_otr ?? 30000000,
                        'skema_pembelian' => $paymentScheme,
                        'dp' => $dpAmount,
                        'tenor_bulan' => $tenor,
                        'angsuran_per_bulan' => $isCredit ? (int) round(($motor?->harga_otr ?? 30000000) * 0.045) : null,
                        'leasing' => $leasingName,
                        'tanggal_follow_up_selanjutnya' => in_array($statusCode, ['hot', 'follow_up']) ? $prospectCreatedAt->copy()->addDays(2)->toDateString() : null,
                        'tanggal_deal' => $dealDate,
                        'catatan' => $prospectNotes,
                        'is_demo' => true,
                        'created_at' => $prospectCreatedAt,
                        'updated_at' => $dealDate ? Carbon::parse($dealDate) : $prospectCreatedAt,
                    ]);

                    // Riwayat Aktivitas Follow-up (Phone / Visit)
                    // Variasikan jumlah aktivitas: deal memiliki 2-3 follow up, hot 2 follow up, follow_up 1-2, batal 1-2, baru 1 follow up
                    $activityCount = match ($statusCode) {
                        'deal' => 2 + ($i % 2),
                        'hot' => 2,
                        'follow_up' => 1 + ($i % 2),
                        'batal' => 1 + ($i % 2),
                        default => 1,
                    };

                    for ($a = 1; $a <= $activityCount; $a++) {
                        $actType = ($a % 2 === 0) ? 'visit' : 'phone';
                        $actTime = $prospectCreatedAt->copy()->addHours($a * 18);

                        $actNotes = match ($statusCode) {
                            'deal' => ($a === 1)
                                ? "Follow-up telepon: Mengonfirmasi minat unit {$motor?->nama_model} ({$color}) dan menghitung simulasi DP."
                                : "Kunjungan showroom / temu konsumen: Verifikasi berkas KTP & KK, penandatanganan PO dan serah terima SPK.",
                            'hot' => ($a === 1)
                                ? "Telepon penjelasan spesifikasi mesin Blue Core / Turbo dan fitur Y-Connect kepada konsumen."
                                : "Kunjungan ke alamat konsumen memberikan simulasi angsuran resmi leasing {$leasingName}.",
                            'follow_up' => "Menghubungi konsumen via telepon/WhatsApp mengenai program diskon dan potongan tenor bulan {$month}.",
                            'baru' => "Pencatatan data kontak awal dan pengiriman katalog digital melalui WhatsApp.",
                            'batal' => ($a === 1)
                                ? "Follow-up telepon untuk klarifikasi persetujuan kredit leasing."
                                : "Komunikasi hasil verifikasi: Konsumen memutuskan membatalkan pembelian karena kendala berkas/anggaran.",
                        };

                        ProspectActivity::create([
                            'prospek_id' => $prospect->id,
                            'user_id' => $salesUser->id,
                            'status_prospek_id' => ($a === $activityCount) ? $statusModel->id : $statusFollowUp->id,
                            'jenis_aktivitas' => $actType,
                            'waktu_aktivitas' => $actTime,
                            'catatan' => $actNotes,
                            'is_demo' => true,
                            'created_at' => $actTime,
                            'updated_at' => $actTime,
                        ]);
                    }

                    // 3. JIKA PROSPEK DEAL -> BUAT TRANSAKSI PENJUALAN VALID
                    if ($statusCode === 'deal') {
                        $dealsGenerated++;
                        $trxNumber = 'TRX-' . $year . sprintf('%02d', $month) . '-' . sprintf('%04d', $trxGlobalCounter++);

                        SalesTransaction::create([
                            'nomor_transaksi' => $trxNumber,
                            'user_id' => $salesUser->id,
                            'sepeda_motor_id' => $motor?->id,
                            'warna_motor_id' => null,
                            'nama_warna' => $color,
                            'jumlah_unit' => 1,
                            'harga_satuan' => $motor?->harga_otr ?? 30000000,
                            'total_harga' => $motor?->harga_otr ?? 30000000,
                            'skema_pembelian' => $paymentScheme,
                            'leasing' => $leasingName,
                            'nama_konsumen' => $custName,
                            'nomor_telepon_konsumen' => $custPhone,
                            'alamat_konsumen' => $custAddress,
                            'prospek_id' => $prospect->id,
                            'tanggal_transaksi' => $dealDate,
                            'status_transaksi' => 'valid',
                            'catatan' => "Penjualan resmi 1 unit {$motor?->nama_model} ({$color}) dealer Yamaha JG Purwakarta.",
                            'dibuat_oleh' => $salesUser->id,
                            'is_demo' => true,
                            'created_at' => Carbon::parse($dealDate)->setHour(14),
                            'updated_at' => Carbon::parse($dealDate)->setHour(14),
                        ]);
                    }

                    $prospectGlobalCounter++;
                }

                // 4. Catat Status Reward Bulanan (SalesReward)
                // Memenuhi syarat jika dealsGenerated >= 15
                $isRewardEligible = ($dealsGenerated >= $targetUnitPerMonth);
                SalesReward::create([
                    'reward_program_id' => $rewardProgram->id,
                    'user_id' => $salesUser->id,
                    'periode_bulan' => $month,
                    'periode_tahun' => $year,
                    'realisasi_unit' => $dealsGenerated,
                    'status' => $isRewardEligible ? 'disetujui' : 'belum_tercapai',
                    'tanggal_verifikasi' => $isRewardEligible ? Carbon::create($year, $month, 28) : null,
                    'diverifikasi_oleh' => $isRewardEligible ? $supervisor->id : null,
                    'tanggal_pemberian' => ($isRewardEligible && $month < 10) ? Carbon::create($year, $month, 28)->addDays(5) : null,
                    'catatan' => $isRewardEligible
                        ? "Pencapaian {$dealsGenerated} unit memenuhi target program reward 15 unit (Bonus Rp 1.500.000 disetujui)."
                        : "Pencapaian {$dealsGenerated} unit (kurang " . ($targetUnitPerMonth - $dealsGenerated) . " unit lagi menuju target 15 unit).",
                    'is_demo' => true,
                ]);

                // 5. Evaluasi Kinerja Bulanan dari Supervisor
                $pctCapaian = round(($dealsGenerated / $targetUnitPerMonth) * 100, 1);
                $statusKinerja = match (true) {
                    $pctCapaian >= 100 => 'Sangat Baik',
                    $pctCapaian >= 80 => 'Baik',
                    $pctCapaian >= 60 => 'Cukup',
                    default => 'Perlu Pembinaan',
                };

                PerformanceEvaluation::create([
                    'user_id' => $salesUser->id,
                    'supervisor_id' => $supervisor->id,
                    'periode_bulan' => $month,
                    'periode_tahun' => $year,
                    'target_unit' => $targetUnitPerMonth,
                    'realisasi_unit' => $dealsGenerated,
                    'persentase_capaian' => $pctCapaian,
                    'peringkat' => 1, // Akan dihitung ulang dinamis di controller
                    'status_kinerja' => $statusKinerja,
                    'catatan_evaluasi' => "Evaluasi pencapaian periode {$month}/{$year}: Realisasi {$dealsGenerated} unit dari target {$targetUnitPerMonth} unit ({$pctCapaian}%).",
                    'rekomendasi' => $isRewardEligible ? 'Pertahankan performa closing dan aktif membimbing rekan sales.' : 'Tingkatkan follow-up terhadap prospek kategori Hot.',
                    'rencana_tindak_lanjut' => 'Monitoring mingguan dan pendampingan negosiasi leasing.',
                    'is_demo' => true,
                ]);
            }
        }

        // 6. TEMUAN LAPANGAN REALISTIS (3 Kasus)
        $nmaxMotor = Motorcycle::where('nama_model', 'like', '%NMAX%')->first() ?? $motorcycles->first();
        $filanoMotor = Motorcycle::where('nama_model', 'like', '%Filano%')->first() ?? $motorcycles->skip(1)->first();
        $aeroxMotor = Motorcycle::where('nama_model', 'like', '%Aerox%')->first() ?? $motorcycles->skip(2)->first();

        FieldFinding::create([
            'user_id' => $salesSiti->id,
            'tanggal_temuan' => now()->subDays(3)->toDateString(),
            'nama_konsumen' => 'Dedi Mulyadi',
            'kontak_konsumen' => '081299881122',
            'sepeda_motor_id' => $nmaxMotor?->id,
            'warna_motor' => 'Turbo Matte Black',
            'kisaran_anggaran' => 38000000,
            'metode_pembayaran' => 'cash',
            'status_minat' => 'tinggi',
            'kendala' => 'Konsumen mencari tipe NMAX Turbo Tech Max warna hitam doff, namun stok unit display sedang kosong.',
            'catatan_komunikasi' => 'Konsumen siap memberikan tanda jadi jika unit dapat dipastikan tiba dalam 5 hari kerja.',
            'rencana_tindak_lanjut' => 'Koordinasi dengan bagian gudang/logistik untuk ketersediaan alokasi unit NMAX Turbo.',
            'tanggal_tindak_lanjut' => now()->addDays(2)->toDateString(),
            'status_tindak_lanjut' => 'sedang_diproses',
            'catatan_hasil_tindak_lanjut' => 'Telah dihubungi PIC logistik, unit dijadwalkan tiba hari Jumat.',
            'is_demo' => true,
        ]);

        FieldFinding::create([
            'user_id' => $salesBudi->id,
            'tanggal_temuan' => now()->subDays(5)->toDateString(),
            'nama_konsumen' => 'Rahmat Hidayat',
            'kontak_konsumen' => '085733221100',
            'sepeda_motor_id' => $filanoMotor?->id,
            'warna_motor' => 'Neo Dull Blue',
            'kisaran_anggaran' => 28000000,
            'metode_pembayaran' => 'kredit',
            'status_minat' => 'sedang',
            'kendala' => 'Konsumen merasa cicilan leasing 35 bulan sedikit melebihi anggaran bulanan.',
            'catatan_komunikasi' => 'Meminta simulasi perbandingan alternatif DP lebih besar agar cicilan per bulan di bawah Rp 1.100.000.',
            'rencana_tindak_lanjut' => 'Kirimkan simulasi angsuran promo paket Adira Finance DP 25%.',
            'tanggal_tindak_lanjut' => now()->subDays(1)->toDateString(),
            'status_tindak_lanjut' => 'selesai',
            'catatan_hasil_tindak_lanjut' => 'Konsumen sepakat dengan skema DP Rp 4.500.000 dan berkas sudah diajukan ke analis leasing.',
            'is_demo' => true,
        ]);

        FieldFinding::create([
            'user_id' => $salesDoni->id,
            'tanggal_temuan' => now()->subDays(2)->toDateString(),
            'nama_konsumen' => 'Agus Priyono',
            'kontak_konsumen' => '081377889900',
            'sepeda_motor_id' => $aeroxMotor?->id,
            'warna_motor' => 'Cyber City',
            'kisaran_anggaran' => 31000000,
            'metode_pembayaran' => 'kredit',
            'status_minat' => 'tinggi',
            'kendala' => 'Berkas domisili KTP luar kota (Bandung Barat), membutuhkan surat keterangan domisili Purwakarta.',
            'catatan_komunikasi' => 'Konsumen bekerja di kawasan industri BIC Purwakarta.',
            'rencana_tindak_lanjut' => 'Bantu verifikasi surat keterangan kerja dari HRD perusahaan untuk persyaratan leasing.',
            'tanggal_tindak_lanjut' => now()->addDays(3)->toDateString(),
            'status_tindak_lanjut' => 'menunggu',
            'catatan_hasil_tindak_lanjut' => null,
            'is_demo' => true,
        ]);

        // 7. RAPAT MINGGUAN SIMULASI
        $meeting1 = Meeting::create([
            'judul_rapat' => 'Rapat Evaluasi Penjualan & Review Target Minggu ke-3',
            'tanggal' => now()->subDays(4)->toDateString(),
            'waktu_mulai' => '08:30:00',
            'waktu_selesai' => '10:00:00',
            'agenda' => '1. Evaluasi realisasi unit Yamaha per tanggal 20. 2. Stok unit NMAX Turbo & Grand Filano. 3. Strategi closing akhir bulan menuju reward 15 unit.',
            'deskripsi' => 'Rapat mingguan rutin seluruh tenaga sales dealer Yamaha JG Purwakarta.',
            'metode' => 'hybrid',
            'link_zoom' => 'https://zoom.us/j/8899112233?pwd=demoYamahaJG2026',
            'nama_tempat' => 'Ruang Rapat Utama Lantai 2',
            'alamat_lokasi' => 'Dealer Yamaha JG Purwakarta, Jl. Veteran No. 12',
            'status' => 'selesai',
            'notulen' => "Poin Keputusan Rapat:\n1. Setiap sales diwajibkan mem-follow up minimal 5 prospek kategori Hot setiap hari.\n2. Alokasi unit NMAX Turbo diprioritaskan bagi konsumen dengan uang muka/tanda jadi lunas.\n3. Sales Siti Rahmawati berhasil mencapai target 16 unit dan berhak atas reward.",
            'hasil_evaluasi' => 'Peningkatan prospek digital sangat positif. Kendala terbesar pada ketersediaan warna matte.',
            'rencana_tindak_lanjut' => 'Supervisor koordinasi logistik untuk alokasi unit akhir bulan.',
            'penanggung_jawab_tindak_lanjut' => 'Supervisor Dealer Utama',
            'batas_waktu_tindak_lanjut' => now()->addDays(3)->toDateString(),
            'status_tindak_lanjut' => 'dalam_proses',
            'dibuat_oleh' => $supervisor->id,
            'is_demo' => true,
        ]);

        foreach ($salesList as $salesUser) {
            MeetingAttendance::create([
                'meeting_id' => $meeting1->id,
                'user_id' => $salesUser->id,
                'status_undangan' => 'diundang',
                'konfirmasi_kehadiran' => ($salesUser->id === $salesRina->id ? 'hadir_online' : 'hadir_offline'),
                'status_kehadiran' => 'hadir',
                'catatan_kehadiran' => ($salesUser->id === $salesRina->id ? 'Hadir via Zoom dari lokasi pameran' : 'Hadir tepat waktu di ruang rapat'),
                'waktu_konfirmasi' => now()->subDays(5),
                'is_demo' => true,
            ]);
        }

        Meeting::create([
            'judul_rapat' => 'Rapat Koordinasi Akhir Bulan & Apresiasi Reward Sales',
            'tanggal' => now()->addDays(4)->toDateString(),
            'waktu_mulai' => '09:00:00',
            'waktu_selesai' => '10:30:00',
            'agenda' => '1. Rekapitulasi final target penjualan bulanan. 2. Penyerahan sertifikat dan bonus insentif reward 15 unit. 3. Sosialisasi target penjualan bulan depan.',
            'deskripsi' => 'Rapat pleno penutupan buku penjualan bulan berjalan.',
            'metode' => 'hybrid',
            'link_zoom' => 'https://zoom.us/j/9900112233?pwd=simakisFinal2026',
            'nama_tempat' => 'Ruang Rapat Utama Lantai 2',
            'alamat_lokasi' => 'Dealer Yamaha JG Purwakarta',
            'status' => 'dijadwalkan',
            'dibuat_oleh' => $supervisor->id,
            'is_demo' => true,
        ]);
    }
}
