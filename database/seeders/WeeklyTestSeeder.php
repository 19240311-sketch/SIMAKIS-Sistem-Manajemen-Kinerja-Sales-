<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WeeklyAnswer;
use App\Models\WeeklyAttempt;
use App\Models\WeeklyQuestion;
use App\Models\WeeklyTest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WeeklyTestSeeder extends Seeder
{
    public function run(): void
    {
        $testsData = [
            // ==========================================
            // TEST 1: AKTIF (Minggu Ini)
            // ==========================================
            [
                'nama_tes' => 'Tes Mingguan 01 — Product Knowledge Yamaha Maxi & Classy',
                'kategori' => 'Product Knowledge Yamaha',
                'deskripsi' => 'Evaluasi mingguan pemahaman spesifikasi teknis dan keunggulan fitur NMAX Turbo, Aerox 155, Grand Filano, dan Fazzio Hybrid.',
                'tanggal_mulai' => now()->subDays(2)->startOfDay(),
                'tanggal_selesai' => now()->addDays(5)->endOfDay(),
                'durasi_menit' => 15,
                'nilai_minimum' => 70,
                'status' => 'published',
                'questions' => [
                    [
                        'pertanyaan' => 'Apa komponen utama yang menggantikan roller konvensional pada transmisi Yamaha NMAX Turbo?',
                        'pilihan_a' => 'Motor Elektrik YECVT (Yamaha Electric CVT)',
                        'pilihan_b' => 'Torque Converter Hidrolik',
                        'pilihan_c' => 'Rantai Baja Otomatis',
                        'pilihan_d' => 'Kopling Manual Basah',
                        'jawaban_benar' => 'A',
                        'penjelasan' => 'NMAX Turbo menggunakan YECVT yang digerakkan motor elektrik elektronik untuk akselerasi instan tanpa jeda roller.',
                    ],
                    [
                        'pertanyaan' => 'Fitur Turbo Y-Shift pada NMAX Turbo memiliki berapa tingkatan deselerasi/akselerasi?',
                        'pilihan_a' => '1 Tingkat',
                        'pilihan_b' => '2 Tingkat',
                        'pilihan_c' => '3 Tingkat (Shift 1, 2, 3)',
                        'pilihan_d' => '5 Tingkat',
                        'jawaban_benar' => 'C',
                        'penjelasan' => 'Tombol Y-Shift pada stang kiri memiliki 3 tingkatan (Rendah, Sedang, Kuat) untuk akselerasi maupun engine brake.',
                    ],
                    [
                        'pertanyaan' => 'Berapa kapasitas bagasi utama yang dimiliki oleh Yamaha Grand Filano Hybrid?',
                        'pilihan_a' => '18 Liter',
                        'pilihan_b' => '22 Liter',
                        'pilihan_c' => '27 Liter (Dilengkapi Lampu LED)',
                        'pilihan_d' => '32 Liter',
                        'jawaban_benar' => 'C',
                        'penjelasan' => 'Bagasi Grand Filano berkapasitas 27 Liter dan memiliki lampu penerangan LED bawaan di dalamnya.',
                    ],
                    [
                        'pertanyaan' => 'Di manakah letak posisi lubang pengisian bahan bakar (Front Refuel) pada Grand Filano?',
                        'pilihan_a' => 'Di bawah jok pengendara',
                        'pilihan_b' => 'Di dashboard kiri bagian depan',
                        'pilihan_c' => 'Di dekat pijakan kaki samping',
                        'pilihan_d' => 'Di bawah lampu belakang',
                        'jawaban_benar' => 'B',
                        'penjelasan' => 'Front Refuel terletak di dashboard kiri depan, praktis isi bensin tanpa perlu turun membuka jok.',
                    ],
                    [
                        'pertanyaan' => 'Berapa detik Electric Power Assist Start pada Fazzio Hybrid memberikan dorongan daya awal?',
                        'pilihan_a' => '3 Detik pertama saat akselerasi',
                        'pilihan_b' => '10 Detik pertama',
                        'pilihan_c' => 'Sepanjang perjalanan tanpa henti',
                        'pilihan_d' => 'Hanya saat gigi mundur',
                        'jawaban_benar' => 'A',
                        'penjelasan' => 'Electric Power Assist Start aktif selama 3 detik pertama saat tuas gas dibuka dari kondisi berhenti.',
                    ],
                    [
                        'pertanyaan' => 'Fitur apa pada Aerox 155 yang memastikan tenaga mesin merata di RPM rendah maupun RPM tinggi?',
                        'pilihan_a' => 'VVA (Variable Valve Actuation)',
                        'pilihan_b' => 'Karburator Flat Slide',
                        'pilihan_c' => 'Direct Air Filter',
                        'pilihan_d' => 'Turbo Intercooler',
                        'jawaban_benar' => 'A',
                        'penjelasan' => 'Teknologi VVA berganti profil noken as pada 6.000 RPM agar tenaga tetap padat di semua putaran mesin.',
                    ],
                    [
                        'pertanyaan' => 'Berapa kapasitas tangki bensin pada skutik Classy Yamaha Fazzio Hybrid?',
                        'pilihan_a' => '4,0 Liter',
                        'pilihan_b' => '4,5 Liter',
                        'pilihan_c' => '5,1 Liter',
                        'pilihan_d' => '6,2 Liter',
                        'jawaban_benar' => 'C',
                        'penjelasan' => 'Kapasitas tangki Fazzio adalah 5,1 Liter untuk jarak tempuh harian yang lebih jauh dan efisien.',
                    ],
                    [
                        'pertanyaan' => 'Apa fungsi fitur Answer Back System pada remote Smart Key Yamaha?',
                        'pilihan_a' => 'Menyetel musik otomatis',
                        'pilihan_b' => 'Memudahkan mencari posisi motor di parkiran dengan bunyi beep & kedipan lampu',
                        'pilihan_c' => 'Mengunci roda belakang secara mekanik',
                        'pilihan_d' => 'Mengecek tekanan angin ban',
                        'jawaban_benar' => 'B',
                        'penjelasan' => 'Answer Back System merespon dengan bunyi dan lampu berkedip ketika tombol remote ditekan.',
                    ],
                    [
                        'pertanyaan' => 'Riding Mode "T-Mode" pada Yamaha NMAX Turbo dioptimalkan untuk kondisi apa?',
                        'pilihan_a' => 'Balapan di sirkuit',
                        'pilihan_b' => 'Town Commuting (harian dalam kota dengan akselerasi halus & irit BBM)',
                        'pilihan_c' => 'Off-road lumpur berbatu',
                        'pilihan_d' => 'Menarik beban berat di atas 300 kg',
                        'jawaban_benar' => 'B',
                        'penjelasan' => 'T-Mode (Town) memberikan respon akselerasi yang halus, nyaman, dan efisien untuk perjalanan dalam kota.',
                    ],
                    [
                        'pertanyaan' => 'Fitur keselamatan apa yang mencegah roda motor selip saat berakselerasi di permukaan jalan licin/basah?',
                        'pilihan_a' => 'TCS (Traction Control System)',
                        'pilihan_b' => 'Side Stand Switch',
                        'pilihan_c' => 'Stop & Start System',
                        'pilihan_d' => 'Smart Motor Generator',
                        'jawaban_benar' => 'A',
                        'penjelasan' => 'TCS secara otomatis membatasi semburan tenaga ke roda belakang jika terdeteksi gejala selip ban.',
                    ],
                ],
            ],

            // ==========================================
            // TEST 2: PERIODE LALU (Riwayat Selesai)
            // ==========================================
            [
                'nama_tes' => 'Tes Mingguan 02 — Sales Skill, Handling Objection & Closing',
                'kategori' => 'Sales Consultant Skill',
                'deskripsi' => 'Tes evaluasi kemampuan probing kebutuhan, negosiasi skema pembiayaan, teknik anti-ghosting, dan eksekusi Go To Deal.',
                'tanggal_mulai' => now()->subDays(12)->startOfDay(),
                'tanggal_selesai' => now()->subDays(4)->endOfDay(),
                'durasi_menit' => 15,
                'nilai_minimum' => 70,
                'status' => 'published',
                'questions' => [
                    [
                        'pertanyaan' => 'Apa tujuan utama seorang Sales Consultant pada tahap awal interaksi dengan konsumen?',
                        'pilihan_a' => 'Langsung menyodorkan brosur harga kredit termahal',
                        'pilihan_b' => 'Menggali kebutuhan sejati (probing) dan memberikan rekomendasi unit yang tepat',
                        'pilihan_c' => 'Memaksa konsumen mengisi formulir SPK',
                        'pilihan_d' => 'Menjelek-jelekkan merk kompetitor',
                        'jawaban_benar' => 'B',
                        'penjelasan' => 'Konsultan penjualan profesional mendengarkan kebutuhan konsumen terlebih dahulu sebelum memberikan solusi produk.',
                    ],
                    [
                        'pertanyaan' => 'Saat konsumen mengatakan "Harganya lebih mahal dari merk sebelah ya?", respon sales yang paling tepat adalah:',
                        'pilihan_a' => 'Langsung memberikan diskon besar-besaran dari kantong pribadi',
                        'pilihan_b' => 'Menjelaskan value jangka panjang: garansi 5 tahun, keiritan Blue Core Hybrid, dan resale value Yamaha',
                        'pilihan_c' => 'Mempersilakan konsumen membeli di merk sebelah saja',
                        'pilihan_d' => 'Mendiamkan pertanyaan konsumen',
                        'jawaban_benar' => 'B',
                        'penjelasan' => 'Fokuskan penjelasan pada keunggulan nilai (Value), teknologi mesin, dan garansi panjang Yamaha.',
                    ],
                    [
                        'pertanyaan' => 'Contoh kalimat "Alternative Choice Closing" yang efektif mengunci keputusan konsumen adalah:',
                        'pilihan_a' => 'Bapak jadi beli apa tidak?',
                        'pilihan_b' => 'Bapak lebih memilih unit motornya dikirim hari Sabtu pagi atau Minggu siang ke rumah?',
                        'pilihan_c' => 'Terserah Bapak saja kapan mau beli.',
                        'pilihan_d' => 'Apakah Bapak punya uang untuk DP?',
                        'jawaban_benar' => 'B',
                        'penjelasan' => 'Alternative Choice Closing mengasumsikan transaksi sudah disetujui dan memfokuskan opsi ke waktu pengiriman.',
                    ],
                    [
                        'pertanyaan' => 'Kapan data konsumen harus segera dipindahkan ke tahap "Go To Deal" di aplikasi SIMPRO?',
                        'pilihan_a' => 'Ketika konsumen baru sekadar bertanya harga lewat WhatsApp',
                        'pilihan_b' => 'Ketika konsumen telah sepakat membeli unit, skema bayar (Cash/Kredit) terpilih, dan berkas KTP siap diproses',
                        'pilihan_c' => 'Ketika konsumen menolak follow-up sales',
                        'pilihan_d' => 'Hanya di akhir bulan saat rekap gaji',
                        'jawaban_benar' => 'B',
                        'penjelasan' => 'Go To Deal digunakan untuk transaksi closing yang sudah memiliki kepastian skema bayar dan data pelengkap.',
                    ],
                    [
                        'pertanyaan' => 'Mengapa pesan follow-up ke calon konsumen harus membawa nilai tambah (Value-Added)?',
                        'pilihan_a' => 'Agar tidak terkesan spamming dan konsumen merasa dihargai dengan info promo/alokasi warna baru',
                        'pilihan_b' => 'Supaya kuota WhatsApp cepat habis',
                        'pilihan_c' => 'Hanya untuk formalitas tugas dari dealer',
                        'pilihan_d' => 'Agar konsumen bosan membaca pesan',
                        'jawaban_benar' => 'A',
                        'penjelasan' => 'Value-added follow-up membangun hubungan profesional dan memicu konsumen untuk segera merespons.',
                    ],
                ],
            ],
        ];

        foreach ($testsData as $tData) {
            $test = WeeklyTest::updateOrCreate(
                ['nama_tes' => $tData['nama_tes']],
                [
                    'slug' => Str::slug($tData['nama_tes']),
                    'kategori' => $tData['kategori'],
                    'deskripsi' => $tData['deskripsi'],
                    'tanggal_mulai' => $tData['tanggal_mulai'],
                    'tanggal_selesai' => $tData['tanggal_selesai'],
                    'durasi_menit' => $tData['durasi_menit'],
                    'nilai_minimum' => $tData['nilai_minimum'],
                    'status' => $tData['status'],
                ]
            );

            foreach ($tData['questions'] as $qIdx => $qData) {
                WeeklyQuestion::updateOrCreate(
                    [
                        'weekly_test_id' => $test->id,
                        'pertanyaan' => $qData['pertanyaan'],
                    ],
                    [
                        'pilihan_a' => $qData['pilihan_a'],
                        'pilihan_b' => $qData['pilihan_b'],
                        'pilihan_c' => $qData['pilihan_c'],
                        'pilihan_d' => $qData['pilihan_d'],
                        'jawaban_benar' => $qData['jawaban_benar'],
                        'penjelasan' => $qData['penjelasan'] ?? null,
                        'urutan' => $qIdx + 1,
                    ]
                );
            }
        }

        // Seed Sample Completed Attempt for Sales 1 (Budi) on Test 2
        $sales1 = User::where('email', 'sales1@simakis.com')->first();
        $sales2 = User::where('email', 'sales2@simakis.com')->first();

        $test2 = WeeklyTest::where('nama_tes', 'like', '%Tes Mingguan 02%')->with('questions')->first();

        if ($sales1 && $test2) {
            $totalQ = $test2->questions->count();
            $attempt1 = WeeklyAttempt::updateOrCreate(
                ['weekly_test_id' => $test2->id, 'user_id' => $sales1->id],
                [
                    'waktu_mulai' => now()->subDays(6)->setTime(9, 0),
                    'waktu_selesai' => now()->subDays(6)->setTime(9, 12),
                    'nilai' => 100,
                    'jumlah_benar' => $totalQ,
                    'jumlah_salah' => 0,
                    'total_soal' => $totalQ,
                    'status' => 'selesai',
                    'status_lulus' => true,
                ]
            );

            foreach ($test2->questions as $q) {
                WeeklyAnswer::updateOrCreate(
                    ['weekly_attempt_id' => $attempt1->id, 'weekly_question_id' => $q->id],
                    [
                        'jawaban_user' => $q->jawaban_benar,
                        'is_correct' => true,
                    ]
                );
            }
        }

        if ($sales2 && $test2) {
            $totalQ = $test2->questions->count();
            $attempt2 = WeeklyAttempt::updateOrCreate(
                ['weekly_test_id' => $test2->id, 'user_id' => $sales2->id],
                [
                    'waktu_mulai' => now()->subDays(5)->setTime(14, 0),
                    'waktu_selesai' => now()->subDays(5)->setTime(14, 11),
                    'nilai' => 80,
                    'jumlah_benar' => 4,
                    'jumlah_salah' => 1,
                    'total_soal' => $totalQ,
                    'status' => 'selesai',
                    'status_lulus' => true,
                ]
            );

            foreach ($test2->questions as $i => $q) {
                $userAns = ($i === 0) ? ($q->jawaban_benar === 'A' ? 'B' : 'A') : $q->jawaban_benar;
                WeeklyAnswer::updateOrCreate(
                    ['weekly_attempt_id' => $attempt2->id, 'weekly_question_id' => $q->id],
                    [
                        'jawaban_user' => $userAns,
                        'is_correct' => ($userAns === $q->jawaban_benar),
                    ]
                );
            }
        }
    }
}
