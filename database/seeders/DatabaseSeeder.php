<?php

namespace Database\Seeders;

use App\Models\Motorcycle;
use App\Models\Prospect;
use App\Models\ProspectActivity;
use App\Models\ProspectStatus;
use App\Models\Source;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MotorcycleSeeder::class,
            SourceSeeder::class,
            ProspectStatusSeeder::class,
            TrainingSeeder::class,
            WeeklyTestSeeder::class,
        ]);

        $sales1 = User::where('email', 'sales1@simakis.com')->first();
        $sales2 = User::where('email', 'sales2@simakis.com')->first();

        $nmax = Motorcycle::where('nama_model', 'like', '%NMAX%')->first() ?? Motorcycle::first();
        $filano = Motorcycle::where('nama_model', 'like', '%Filano%')->first() ?? Motorcycle::first();
        $aerox = Motorcycle::where('nama_model', 'like', '%Aerox%')->first() ?? Motorcycle::first();
        $xsr = Motorcycle::where('nama_model', 'like', '%XSR%')->first() ?? Motorcycle::first();

        $sourceWalkin = Source::where('nama_sumber', 'like', '%Walk%')->first() ?? Source::first();
        $sourcePameran = Source::where('nama_sumber', 'like', '%Pameran%')->first() ?? Source::first();
        $sourceMedsos = Source::where('nama_sumber', 'like', '%Sosial%')->first() ?? Source::first();

        $statusBaru = ProspectStatus::where('kode', 'baru')->first() ?? ProspectStatus::first();
        $statusFollowUp = ProspectStatus::where('kode', 'follow_up')->first() ?? ProspectStatus::first();
        $statusHot = ProspectStatus::where('kode', 'hot')->first() ?? ProspectStatus::first();
        $statusDeal = ProspectStatus::where('kode', 'deal')->first() ?? ProspectStatus::first();
        $statusBatal = ProspectStatus::where('kode', 'batal')->first() ?? ProspectStatus::first();

        // Sample Prospect 1 - Hot (Sales 1)
        $p1 = Prospect::create([
            'user_id' => $sales1->id,
            'sepeda_motor_id' => $nmax->id,
            'sumber_prospek_id' => $sourceWalkin->id,
            'status_prospek_id' => $statusHot->id,
            'nama_konsumen' => 'Ahmad Rizky Pratama',
            'nomor_telepon' => '081234567890',
            'email' => 'ahmad.rizky@example.com',
            'alamat' => 'Jl. Pemuda No. 45, Jakarta Timur',
            'kelurahan' => 'Rawamangun',
            'kecamatan' => 'Pulo Gadung',
            'kota' => 'Jakarta Timur',
            'provinsi' => 'DKI Jakarta',
            'warna_motor_diminati' => 'Magma Black',
            'harga_otr' => $nmax->harga_otr,
            'tahap_data' => 'prospek',
            'tanggal_follow_up_selanjutnya' => Carbon::now()->addDays(2),
            'catatan' => 'Tertarik NMAX Turbo warna Magma Black. Ingin simulasi kredit DP 15%.',
        ]);

        // Activities for P1
        ProspectActivity::create([
            'prospek_id' => $p1->id,
            'user_id' => $sales1->id,
            'status_prospek_id' => $statusFollowUp->id,
            'jenis_aktivitas' => 'phone',
            'waktu_aktivitas' => Carbon::now()->subDays(5),
            'catatan' => 'Menghubungi via Phone. Konsumen menanyakan rincian skema angsuran 35 bulan.',
        ]);

        ProspectActivity::create([
            'prospek_id' => $p1->id,
            'user_id' => $sales1->id,
            'status_prospek_id' => $statusHot->id,
            'jenis_aktivitas' => 'visit',
            'waktu_aktivitas' => Carbon::now()->subDays(1),
            'catatan' => 'Kunjungan rumah (Visit). Konsumen berminat ambil unit minggu ini, menunggu persetujuan leasing.',
        ]);

        // Sample Prospect 2 - Follow Up (Sales 1)
        $p2 = Prospect::create([
            'user_id' => $sales1->id,
            'sepeda_motor_id' => $filano->id,
            'sumber_prospek_id' => $sourceMedsos->id,
            'status_prospek_id' => $statusFollowUp->id,
            'nama_konsumen' => 'Dewi Anggraini',
            'nomor_telepon' => '085711223344',
            'email' => 'dewi.ang@example.com',
            'alamat' => 'Jl. Mawar No. 12, Bekasi',
            'kelurahan' => 'Pekayon Jaya',
            'kecamatan' => 'Bekasi Selatan',
            'kota' => 'Bekasi',
            'provinsi' => 'Jawa Barat',
            'warna_motor_diminati' => 'Neo Pink Mauve',
            'harga_otr' => $filano->harga_otr,
            'tahap_data' => 'prospek',
            'tanggal_follow_up_selanjutnya' => Carbon::now()->addDays(3),
            'catatan' => 'Tanya ketersediaan warna Neo Pink Mauve.',
        ]);

        ProspectActivity::create([
            'prospek_id' => $p2->id,
            'user_id' => $sales1->id,
            'status_prospek_id' => $statusFollowUp->id,
            'jenis_aktivitas' => 'phone',
            'waktu_aktivitas' => Carbon::now()->subDays(2),
            'catatan' => 'Follow up via Phone. Konsumen minta dihubungi kembali akhir pekan setelah gajian.',
        ]);

        // Sample Prospect 3 - Deal (Sales 2)
        $p3 = Prospect::create([
            'user_id' => $sales2->id,
            'sepeda_motor_id' => $aerox->id,
            'sumber_prospek_id' => $sourcePameran->id,
            'status_prospek_id' => $statusDeal->id,
            'nama_konsumen' => 'Hendra Wijaya',
            'nomor_telepon' => '087899887766',
            'email' => 'hendra.w@example.com',
            'alamat' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
            'kelurahan' => 'Kuningan Barat',
            'kecamatan' => 'Mampang Prapatan',
            'kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
            'warna_motor_diminati' => 'Cyber City Livery',
            'harga_otr' => $aerox->harga_otr,
            'tahap_data' => 'deal',
            'nomor_ktp' => '3171012304900001',
            'tanggal_lahir' => '1990-04-23',
            'pekerjaan' => 'Karyawan Swasta',
            'skema_pembelian' => 'Cash',
            'leasing' => 'Cash Dealer / Tunai Langsung',
            'metode_pembayaran' => 'Transfer Bank BCA',
            'tanggal_deal' => Carbon::now()->subDays(3),
            'catatan' => 'Pembelian Cash Aerox Alpha 155 Cyber City lunas, unit siap kirim.',
        ]);

        ProspectActivity::create([
            'prospek_id' => $p3->id,
            'user_id' => $sales2->id,
            'status_prospek_id' => $statusDeal->id,
            'jenis_aktivitas' => 'visit',
            'waktu_aktivitas' => Carbon::now()->subDays(3),
            'catatan' => 'Konsumen datang ke dealer (Visit) & SPK lunas transfer.',
        ]);

        // Sample Prospect 4 - Baru (Sales 2)
        Prospect::create([
            'user_id' => $sales2->id,
            'sepeda_motor_id' => $xsr->id,
            'sumber_prospek_id' => $sourceWalkin->id,
            'status_prospek_id' => $statusBaru->id,
            'nama_konsumen' => 'Bambang Kusuma',
            'nomor_telepon' => '081399881122',
            'email' => 'bambang.k@example.com',
            'alamat' => 'Jl. Tebet Raya No. 10',
            'kelurahan' => 'Tebet Barat',
            'kecamatan' => 'Tebet',
            'kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
            'warna_motor_diminati' => 'Metallic Black Elegance',
            'harga_otr' => $xsr->harga_otr,
            'tahap_data' => 'prospek',
            'tanggal_follow_up_selanjutnya' => Carbon::now()->addDays(1),
            'catatan' => 'Baru walk-in konsultasi motor retro XSR 155.',
        ]);
    }
}
