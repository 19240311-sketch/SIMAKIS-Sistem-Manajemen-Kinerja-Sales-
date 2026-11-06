<?php

namespace Database\Seeders;

use App\Models\Training;
use App\Models\TrainingMaterial;
use App\Models\TrainingQuiz;
use App\Models\User;
use App\Models\UserQuizResult;
use App\Models\UserTrainingProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        $trainingsData = [
            // ==========================================
            // 1. PRODUCT KNOWLEDGE
            // ==========================================
            [
                'nama_training' => 'Yamaha Fazzio Hybrid — Product Knowledge',
                'kategori' => 'product_knowledge',
                'deskripsi' => 'Kuasai keunggulan motor Classy Yamaha Fazzio: Teknologi Blue Core Hybrid 125cc, Electric Power Assist Start, Smart Key System, dan aksesoris personalisasi untuk generasi muda.',
                'estimasi_waktu' => '15 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Pengenalan & Positioning Fazzio Hybrid',
                        'isi_materi' => "### Konsep Desain & Target Segmen\nYamaha Fazzio Hybrid dihadirkan sebagai pelopor skutik 125cc berteknologi Hybrid di segmen Classy Yamaha. Didesain untuk generasi Gen-Z dan profesional muda yang mengutamakan gaya hidup stylish, ekspresif, dan mobilitas perkotaan yang efisien.\n\n**Poin Kunci Produk:**\n- **Classy Lifestyle**: Tampilan unik, modern retro dengan lampu depan LED oval khas.\n- **Kapasitas Tangki**: 5,1 Liter untuk jarak tempuh harian lebih jauh tanpa sering isi bensin.\n- **Bagasi Luas**: 17,8 Liter muat barang harian, jaket, dan perlengkapan kerja.",
                    ],
                    [
                        'judul_materi' => 'Teknologi Mesin Blue Core Hybrid 125cc',
                        'isi_materi' => "### Cara Kerja Blue Core Hybrid\nTeknologi Hybrid pada Fazzio bekerja dengan mengkombinasikan dua sumber tenaga:\n1. **Mesin Bensin 125cc Blue Core** yang bertenaga dan irit.\n2. **Electric Power Assist Start** yang memanfaatkan daya aki melalui Smart Motor Generator (SMG).\n\n**Keuntungan Bagi Konsumen:**\n- **Akselerasi Awal Lebih Ringan & Halus**: Bantuan tenaga dorong selama 3 detik pertama saat stop-and-go di lampu merah atau tanjakan.\n- **Efisiensi Bahan Bakar Tinggi**: Dilengkapi Stop & Start System (SSS) otomatis mematikan mesin saat berhenti lebih dari 5 detik.\n- **One Push Start**: Menyalakan mesin dengan sekali sentuhan cepat tanpa suara kasar.",
                    ],
                    [
                        'judul_materi' => 'Fitur Kepraktisan & Konektivitas Y-Connect',
                        'isi_materi' => "### Fitur Unggulan untuk Aktivitas Sehari-hari\n- **Smart Key System (Keyless)**: Dilengkapi fitur Answer Back System untuk memudahkan pencarian di area parkir.\n- **Full Digital Speedometer**: Desain layar informatif dengan indikator notifikasi pesan, baterai, dan konsumsi BBM.\n- **Electric Power Socket**: Praktis untuk mengisi daya smartphone saat berkendara.\n- **Double Hook Carabiner**: Gantungan ganda yang aman untuk membawa belanjaan.",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Berapa detik Electric Power Assist Start bekerja memberikan dorongan tenaga awal saat akselerasi?',
                        'pilihan_jawaban' => ['1 detik', '3 detik', '5 detik', '10 detik'],
                        'jawaban_benar' => '3 detik',
                        'penjelasan' => 'Electric Power Assist Start memberikan dorongan tenaga elektrik pada 3 detik pertama akselerasi saat tuas gas dibuka dari kondisi diam.',
                    ],
                    [
                        'pertanyaan' => 'Fitur apa yang memudahkan konsumen menemukan posisi motor Fazzio di area parkir yang padat?',
                        'pilihan_jawaban' => ['Y-Connect Navigation', 'Answer Back System pada Smart Key', 'Electric Power Socket', 'Stop & Start System'],
                        'jawaban_benar' => 'Answer Back System pada Smart Key',
                        'penjelasan' => 'Answer Back System pada remote Smart Key membuat motor berkedip dan berbunyi beep sehingga cepat ditemukan.',
                    ],
                    [
                        'pertanyaan' => 'Berapakah kapasitas tangki bahan bakar pada Yamaha Fazzio Hybrid?',
                        'pilihan_jawaban' => ['4,2 Liter', '4,8 Liter', '5,1 Liter', '6,0 Liter'],
                        'jawaban_benar' => '5,1 Liter',
                        'penjelasan' => 'Kapasitas tangki bensin Fazzio adalah 5,1 Liter, memberikan daya jelajah lebih nyaman tanpa sering mampir ke SPBU.',
                    ],
                ],
            ],
            [
                'nama_training' => 'Yamaha Grand Filano Hybrid-Connected',
                'kategori' => 'product_knowledge',
                'deskripsi' => 'Panduan lengkap skutik luxury fashion Yamaha Grand Filano: Desain neo-retro premium, TFT Sub Display warna, Convenient Front Refuel, dan kenyamanan suspensi.',
                'estimasi_waktu' => '20 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Luxury Neo-Retro Design & Pencahayaan LED Penuh',
                        'isi_materi' => "### Elegansi Desain Kelas Atas\nGrand Filano diciptakan dengan siluet bodi mewah Eropa (Neo-Retro Luxury). Dilengkapi seluruh sistem pencahayaan LED: Diamond Shape LED Headlight, LED Turn Signals, Unique LED Tail Light, hingga lampu LED penerang di dalam bagasi (LED Bagasi).\n\n**Nilai Jual Utama:**\n- Tampilan berkelas yang meningkatkan gengsi dan rasa percaya diri konsumen perkotaan.\n- Posisi duduk ergonomis dengan jok premium bertekstur elegan.",
                    ],
                    [
                        'judul_materi' => 'Convenient Front Refuel & Bagasi Ekstra Besar 27L',
                        'isi_materi' => "### Kemudahan Isi Bensin Tanpa Buka Jok\n- **Front Refuel**: Lubang pengisian bensin berada di dashboard kiri depan, sehingga pengendara tidak perlu turun membuka jok saat mengisi bahan bakar.\n- **Bagasi Luas 27 Liter**: Kapasitas bagasi terbesar di kelasnya yang dilengkapi lampu LED pencahayaan otomatis, muat helm dan tas kerja bersamaan.",
                    ],
                    [
                        'judul_materi' => 'TFT Sub Display Color & Mesin Hybrid 125cc',
                        'isi_materi' => "### Panel Instrumen Digital Berwarna\nGrand Filano memiliki layar ganda: LCD Digital Speedometer utama dan TFT Sub Display berwarna animasi (Welcome-Goodbye message, Fuel Economy, Power Assist Indicator).\n\nMesin Blue Core Hybrid 125cc menghasilkan tenaga responsif dan sangat senyap, ideal untuk mobilitas premium harian.",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Di manakah letak lubang pengisian bahan bakar (Front Refuel) pada Grand Filano?',
                        'pilihan_jawaban' => ['Di bawah jok belakang', 'Di dashboard kiri bagian depan', 'Di tengah stang kemudi', 'Di bawah footstep penumpang'],
                        'jawaban_benar' => 'Di dashboard kiri bagian depan',
                        'penjelasan' => 'Front Refuel di dashboard kiri depan memudahkan konsumen mengisi bensin tanpa perlu repot membuka jok.',
                    ],
                    [
                        'pertanyaan' => 'Berapakah kapasitas bagasi bagasi Yamaha Grand Filano yang dilengkapi lampu LED?',
                        'pilihan_jawaban' => ['18 Liter', '22 Liter', '27 Liter', '30 Liter'],
                        'jawaban_benar' => '27 Liter',
                        'penjelasan' => 'Kapasitas bagasi Grand Filano adalah 27 Liter, terbesar di kelasnya dan dilengkapi lampu LED bawaan.',
                    ],
                ],
            ],
            [
                'nama_training' => 'Yamaha NMAX Turbo — The Ultimate Maxi',
                'kategori' => 'product_knowledge',
                'deskripsi' => 'Bedah teknologi revolusioner YECVT (Yamaha Electric Continuously Variable Transmission), Riding Mode (T-Mode & S-Mode), Turbo Y-Shift, dan TFT Navigation System.',
                'estimasi_waktu' => '25 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Revolusi YECVT & Sensasi Sensasional Turbo',
                        'isi_materi' => "### Apa itu YECVT (Yamaha Electric CVT)?\nNMAX Turbo mengganti sistem roller konvensional dengan motor penggerak elektrik elektronik YECVT. Hal ini memberikan sensasi akselerasi seketika tanpa jeda lag.\n\n**Dua Mode Berkendara:**\n1. **T-Mode (Town Commuting)**: Karakter akselerasi halus, santai, dan sangat hemat konsumsi bahan bakar.\n2. **S-Mode (Sport Touring)**: Karakter akselerasi lebih agresif, responsif untuk menyalip dan perjalanan luar kota.",
                    ],
                    [
                        'judul_materi' => 'Fitur Turbo Y-Shift (3 Tingkatan Deselerasi & Akselerasi)',
                        'isi_materi' => "### Menggunakan Tombol Y-Shift di Stang Kiri\nFitur Turbo Y-Shift memberikan 3 tingkatan (Shift 1 - Rendah, Shift 2 - Sedang, Shift 3 - Kuat):\n- **Saat Menyalip / Berakselerasi**: Menekan tombol Y-Shift meningkatkan RPM mesin seketika untuk lonjakan tenaga kilat.\n- **Saat Menurun / Tikungan**: Memberikan efek Engine Brake elektrik yang membantu pengereman lebih presisi dan stabil.",
                    ],
                    [
                        'judul_materi' => 'Dual Channel ABS & TFT Navigation System',
                        'isi_materi' => "### Keamanan & Navigasi Canggih\n- **Dual Channel ABS + TCS (Traction Control System)**: Mencegah ban terkunci saat rem mendadak dan roda selip di jalan licin atau basah.\n- **TFT Navigation Screen**: Terkoneksi dengan Garmin StreetCross untuk peta belokan demi belokan langsung di panel speedometer.",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Komponen apa yang menggantikan roller konvensional pada NMAX Turbo untuk menghasilkan akselerasi instan?',
                        'pilihan_jawaban' => ['YECVT (Electric CVT Motor)', 'Turbocharger mekanikal oli', 'V-Belt rantai baja', 'Manual Clutch'],
                        'jawaban_benar' => 'YECVT (Electric CVT Motor)',
                        'penjelasan' => 'YECVT menggunakan motor elektrik yang diatur ECU untuk mengubah rasio puli secara instan tanpa gesekan roller konvensional.',
                    ],
                    [
                        'pertanyaan' => 'Berapa tingkatan dorongan akselerasi/deselerasi yang dimiliki fitur Turbo Y-Shift?',
                        'pilihan_jawaban' => ['1 Tingkat', '2 Tingkat', '3 Tingkat (Shift 1, 2, 3)', '5 Tingkat'],
                        'jawaban_benar' => '3 Tingkat (Shift 1, 2, 3)',
                        'penjelasan' => 'Fitur Turbo Y-Shift memiliki 3 tingkatan yang dapat diatur lewat tombol stang kiri: Rendah (1), Sedang (2), dan Kuat (3).',
                    ],
                ],
            ],
            [
                'nama_training' => 'Yamaha Aerox 155 Connected — Super Sport Scooter',
                'kategori' => 'product_knowledge',
                'deskripsi' => 'Penguasaan power-to-weight ratio terbaik di kelasnya: Mesin 155cc VVA, desain DNA R-Series, Smart Key, dan karakter handling sporty.',
                'estimasi_waktu' => '15 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'DNA Super Sport & Power-to-Weight Ratio',
                        'isi_materi' => "### Performa Paling Sporty di Kelas Skutik 155cc\nYamaha Aerox 155 mengusung DNA motor sport Yamaha dengan rasio tenaga berbanding bobot terbaik. Mesin 155cc Liquid Cooled dilengkapi teknologi **Variable Valve Actuation (VVA)** yang memastikan tenaga maksimal merata dari putaran bawah hingga atas.",
                    ],
                    [
                        'judul_materi' => 'Fitur Keselamatan & Kenyamanan Sporty',
                        'isi_materi' => "### Spesifikasi Unggulan:\n- **Ban Tubeless Super Lebar**: Depan 110/80-14 dan belakang 140/70-14 untuk kestabilan manuver menikung.\n- **Sub Tank Suspension**: Tabung peredam kejut belakang untuk redaman lebih stabil di berbagai kontur jalan.\n- **Lampu Hazard & LED**: Visibilitas maksimal di malam hari.",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Teknologi mesin apa yang membuat tenaga Yamaha Aerox 155 tetap bertenaga di putaran bawah maupun putaran atas?',
                        'pilihan_jawaban' => ['VVA (Variable Valve Actuation)', 'Carburator Venturi', 'Air Injection', 'Supercharger'],
                        'jawaban_benar' => 'VVA (Variable Valve Actuation)',
                        'penjelasan' => 'VVA secara otomatis berpindah profil noken as di 6.000 RPM sehingga tenaga selalu padat di setiap rentang putaran mesin.',
                    ],
                ],
            ],
            [
                'nama_training' => 'Yamaha Gear 125 / Ultima — Skutik Multiguna',
                'kategori' => 'product_knowledge',
                'deskripsi' => 'Keunggulan skutik serbaguna harian: Desain tangguh, gantungan ganda, pijakan kaki anak, Smart Motor Generator, dan harga terjangkau dengan DP ramah kantong.',
                'estimasi_waktu' => '15 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Konsep Matic Multiguna untuk Keluarga & Usaha',
                        'isi_materi' => "### Pilihan Cerdas Mobilitas Harian\nYamaha Gear 125 dirancang bagi konsumen yang aktif, dinamis, dan membutuhkan kendaraan tangguh untuk aktivitas keluarga, antar-jemput anak sekolah, hingga belanja usaha harian.\n\n**Keunggulan Utama:**\n- **Pijakan Kaki Khusus Anak (Child Footstep)**: Lebih aman dan nyaman saat membawa anak kecil.\n- **Double Hook**: Bawa barang bawaan lebih banyak tanpa goyang.\n- **Pelindung Bodi Samping**: Melindungi bodi dari goresan saat di parkiran sempit.",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Fitur apa pada bodi Yamaha Gear 125 yang sangat berguna untuk keamanan membawa anak kecil?',
                        'pilihan_jawaban' => ['Pijakan Kaki Anak (Child Footstep)', 'Remote Keyless', 'Windshield Tinggi', 'Velg Jari-jari'],
                        'jawaban_benar' => 'Pijakan Kaki Anak (Child Footstep)',
                        'penjelasan' => 'Pijakan kaki anak memberikan posisi kaki yang pas dan aman saat anak dibonceng di depan.',
                    ],
                ],
            ],
            [
                'nama_training' => 'Yamaha XSR 155, WR155R & R15 Connected',
                'kategori' => 'product_knowledge',
                'deskripsi' => 'Spesifikasi lini sport Yamaha: Karakter Born to be Free pada XSR 155, The Real Adventure WR155R, dan teknologi balap Quick Shifter / Traction Control pada R15.',
                'estimasi_waktu' => '20 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Yamaha XSR 155 — Born to be Free Heritage',
                        'isi_materi' => "### Sport Heritage Terbaik di Indonesia\nXSR 155 menggabungkan keindahan desain timeless klasik dengan performa modern 155cc VVA, suspensi Upside Down (USD), Assist & Slipper Clutch, dan tangki Drip-Shaped ikonik.",
                    ],
                    [
                        'judul_materi' => 'Yamaha WR155R — The Real Adventure Partner',
                        'isi_materi' => "### Rajanya Motor Trail 150cc\nWR155R mengusung mesin 155cc berpendingin cairan (radiator) bertenaga 16,7 HP, suspensi depan teleskopik diameter 41mm terpanjang, dan kapasitas tangki 8,1 Liter untuk trabas jarak jauh.",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Berapa kapasitas tangki bensin motor trail Yamaha WR155R untuk kebutuhan adventure?',
                        'pilihan_jawaban' => ['4,5 Liter', '6,0 Liter', '8,1 Liter', '10,5 Liter'],
                        'jawaban_benar' => '8,1 Liter',
                        'penjelasan' => 'WR155R memiliki tangki bensin 8,1 Liter yang merupakan terbesar di kelas motor trail 150cc.',
                    ],
                ],
            ],

            // ==========================================
            // 2. SALES SKILL
            // ==========================================
            [
                'nama_training' => 'Teknik Menggali Kebutuhan Konsumen (Probing)',
                'kategori' => 'sales_skill',
                'deskripsi' => 'Kuasai teknik bertanya efektif dengan metode OPEN QUESTIONS untuk memahami kebutuhan sejati, anggaran, dan kriteria pemilihan motor calon konsumen.',
                'estimasi_waktu' => '15 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Prinsip Dasar Probing & Mendengarkan Aktif',
                        'isi_materi' => "### Jangan Langsung Menjual, Dengarkan Dulu!\nBanyak sales gagal karena langsung buru-buru menyodorkan brosur dan harga tanpa tahu apa yang sebenarnya dicari oleh konsumen.\n\n**3 Pertanyaan Wajib Saat Konsumen Datang/Dihubungi:**\n1. *\"Rencananya motor ini akan lebih sering digunakan untuk rute mana saja, Pak/Bu?\"* (Mengetahui kebutuhan fungsional)\n2. *\"Apakah lebih mengutamakan kepraktisan bagasi, efisiensi bahan bakar, atau performa akselerasi?\"* (Mengetahui prioritas fitur)\n3. *\"Untuk pembelian nanti, Bapak/Ibu lebih nyaman dengan opsi tunai atau paket promo DP ringan bulanan?\"* (Mengetahui skema anggaran)",
                    ],
                    [
                        'judul_materi' => 'Menemukan Motif Emosional Pembelian (Hot Button)',
                        'isi_materi' => "### Rasional vs Emosional\nKonsumen membeli karena alasan emosional (ingin tampil keren, ingin hadiah untuk anak, ingin hemat pengeluaran) lalu membenarkannya dengan logika.\n\nKetika Sales menemukan *Hot Button* konsumen (misal: ingin motor yang tidak pegal dipakai harian), fokuskan presentasi produk pada jok ergonomis dan suspensi empuk motor tersebut.",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Mengapa teknik Probing (bertanya terbuka) harus dilakukan di awal sebelum menjelaskan harga?',
                        'pilihan_jawaban' => [
                            'Agar konsumen tidak banyak bertanya',
                            'Untuk memahami kebutuhan spesifik dan kriteria utama konsumen sehingga rekomendasi unit tepat sasaran',
                            'Agar sales bisa memotong pembicaraan konsumen',
                            'Hanya sebagai formalitas basa-basi',
                        ],
                        'jawaban_benar' => 'Untuk memahami kebutuhan spesifik dan kriteria utama konsumen sehingga rekomendasi unit tepat sasaran',
                        'penjelasan' => 'Probing membantu sales memberikan rekomendasi motor yang paling sesuai dengan kebutuhan dan isi kantong konsumen.',
                    ],
                ],
            ],
            [
                'nama_training' => 'Handling Objection & Negosiasi Harga',
                'kategori' => 'sales_skill',
                'deskripsi' => 'Solusi jitu menghadapi keberatan konsumen seperti "Harga kemahalan", "Mau diskusi dulu dengan pasangan", atau "Bandingkan dengan merk sebelah".',
                'estimasi_waktu' => '20 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Menghadapi Keberatan: "Harganya Kemahalan"',
                        'isi_materi' => "### Mengubah Fokus dari Harga ke Nilai (Value)\nKetika konsumen mengatakan *\"Harganya lebih mahal dari merk sebelah ya?\"*, jangan langsung banting diskon!\n\n**Gunakan Rumus 'Feel, Felt, Found':**\n- *\"Saya sangat memahami pertimbangan Bapak mengenai anggaran. Banyak konsumen kami sebelumnya juga merasakan hal yang sama...\"*\n- *\"Namun setelah mereka mencoba dan merasakan teknologi Blue Core Hybrid/VVA yang sangat irit bensin serta garansi frame & kelistrikan Yamaha hingga 5 tahun, mereka menyadari biaya operasional jangka panjangnya justru jauh lebih hemat ratusan ribu tiap bulannya.\"*",
                    ],
                    [
                        'judul_materi' => 'Menghadapi Keberatan: "Saya Pikir-pikir Dulu / Tanya Pasangan"',
                        'isi_materi' => "### Langkah Taktis:\n1. Hargai keputusannya: *\"Tentu Pak, keputusan bersama pasangan sangatlah penting.\"*\n2. Tanyakan kepastian: *\"Kira-kira dari fitur unit atau skema hitungan tadi, apakah ada bagian yang masih membuat Bapak/Ibu ragu?\"*\n3. Berikan alasan untuk segera follow-up: *\"Boleh saya kirimkan simulasi PDF dan foto warnanya ke WhatsApp agar Bapak bisa diskusikan malam ini bersama Ibu? Besok siang saya hubungi sebentar untuk memastikan ketersediaan alokasi warna ya Pak.\"*",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Apa langkah pertama yang tepat saat konsumen mengatakan ingin diskusi dulu dengan pasangan?',
                        'pilihan_jawaban' => [
                            'Memaksa konsumen langsung tanda tangan SPK hari itu juga',
                            'Menghargai keputusannya, tanyakan bagian yang belum jelas, lalu tawarkan mengirim simulasi via WhatsApp untuk bahan diskusi',
                            'Membatalkan prospek dan tidak perlu dihubungi lagi',
                            'Langsung memberikan diskon besar-besaran',
                        ],
                        'jawaban_benar' => 'Menghargai keputusannya, tanyakan bagian yang belum jelas, lalu tawarkan mengirim simulasi via WhatsApp untuk bahan diskusi',
                        'penjelasan' => 'Memberikan waktu dan bahan diskusi tertulis ke WhatsApp menjaga hubungan tetap hangat untuk closing follow-up berikutnya.',
                    ],
                ],
            ],
            [
                'nama_training' => 'Teknik Follow-Up Efektif & Anti-Ghosting',
                'kategori' => 'sales_skill',
                'deskripsi' => 'Strategi follow-up via WhatsApp dan telepon yang sopan, konsisten, bernilai tambah (Value-Added Follow Up), dan tidak terkesan spamming.',
                'estimasi_waktu' => '15 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Aturan Emas Follow-Up: Always Give Value',
                        'isi_materi' => "### Jangan Hanya Bertanya: \"Jadi ambil motornya gak Pak?\"\nFollow-up yang baik selalu membawa informasi baru yang bermanfaat bagi konsumen.\n\n**Contoh Pesan Follow-Up Efektif:**\n- *\"Selamat siang Pak Hendra, menginfokan unit NMAX Turbo warna Magma Black yang kemarin Bapak minati baru saja tiba 1 unit di dealer kami. Jika Bapak berkenan, unit ini bisa kami keepkan alokasinya untuk Bapak hari ini.\"*\n- *\"Selamat pagi Bu Dewi, hari ini sedang ada promo potongan tenor 2 bulan untuk program leasing Grand Filano khusus minggu ini.\"*",
                    ],
                    [
                        'judul_materi' => 'Jadwal & Ritme Follow-Up di Aplikasi SIMPRO',
                        'isi_materi' => "### Gunakan Fitur Reminder SIMPRO\n- **Follow-up 1 (H+1)**: Kirimkan ucapan terima kasih telah mampir/konsultasi + ringkasan spesifikasi.\n- **Follow-up 2 (H+3)**: Berikan info promo leasing / kemudahan berkas KTP.\n- **Follow-up 3 (H+7)**: Tawarkan test-ride atau kunjungan ke rumah (Visit).\nCatat setiap respon di histori aktivitas SIMPRO agar data tidak hilang!",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Mengapa pesan follow-up harus selalu membawa informasi bernilai tambah (Value-Added)?',
                        'pilihan_jawaban' => [
                            'Agar konsumen merasa dihormati, tidak terganggu spam, dan terpicu segera mengambil keputusan',
                            'Supaya pesan WhatsApp terlihat panjang',
                            'Agar sales tidak perlu menelpon konsumen',
                            'Hanya untuk menghabiskan kuota internet',
                        ],
                        'jawaban_benar' => 'Agar konsumen merasa dihormati, tidak terganggu spam, dan terpicu segera mengambil keputusan',
                        'penjelasan' => 'Value-added follow up membangun reputasi sales sebagai konsultan terpercaya, bukan sekadar penagih pesanan.',
                    ],
                ],
            ],
            [
                'nama_training' => 'Teknik Closing & Transaksi Go To Deal',
                'kategori' => 'sales_skill',
                'deskripsi' => 'Kuasai Closing Signals dan teknik Direct Closing, Assumptive Closing, & Urgency Closing untuk mengunci SPK dan mengubah prospek menjadi Go To Deal lunas.',
                'estimasi_waktu' => '20 Menit',
                'materials' => [
                    [
                        'judul_materi' => 'Mengenali Sinyal Closing (Buying Signals)',
                        'isi_materi' => "### Tanda-tanda Konsumen Siap Beli:\n1. Mulai menanyakan ketersediaan warna spesifik (*\"Warna hitamnya ready stock atau inden?\"*)\n2. Menanyakan syarat berkas (*\"KTP daerah bisa diproses gak ya?\"*)\n3. Menanyakan estimasi waktu pengiriman (*\"Kalau deal hari ini, STNK dan motor kapan dikirim?\"*)\n4. Mengajak keluarga atau teman melihat langsung motor.",
                    ],
                    [
                        'judul_materi' => '3 Teknik Closing Terbaik untuk Sales Motor',
                        'isi_materi' => "### Eksekusi Closing:\n1. **Assumptive Closing**: Anggap konsumen sudah sepakat. *\"Untuk proses STNK dan plat nomornya, apakah menggunakan nama Bapak pribadi di KTP ini?\"*\n2. **Alternative Choice Closing**: Berikan pilihan positif. *\"Bapak lebih memilih unit diantar Sabtu pagi atau Minggu siang ke rumah?\"*\n3. **Urgency Closing**: Berikan batas waktu program. *\"Kebetulan subsidi DP Rp 1,5 Juta ini kuotanya hanya tersisa 2 slot untuk pengajuan minggu ini Pak.\"*\n\nSetelah deal, langsung input data di formulir **Go To Deal SIMPRO**!",
                    ],
                ],
                'quizzes' => [
                    [
                        'pertanyaan' => 'Manakah contoh kalimat "Alternative Choice Closing" yang efektif mengunci transaksi?',
                        'pilihan_jawaban' => [
                            'Bapak mau beli apa tidak?',
                            'Bapak lebih memilih unit motornya diantar hari Sabtu pagi atau Minggu siang ke rumah?',
                            'Silakan pikir-pikir dulu sampai bulan depan.',
                            'Harganya mahal kan Pak?',
                        ],
                        'jawaban_benar' => 'Bapak lebih memilih unit motornya diantar hari Sabtu pagi atau Minggu siang ke rumah?',
                        'penjelasan' => 'Alternative Choice Closing mengarahkan pikiran konsumen ke opsi waktu pengiriman yang sudah mengasumsikan transaksi terjadi.',
                    ],
                ],
            ],
        ];

        // Seed Trainings, Materials, Quizzes
        foreach ($trainingsData as $index => $tData) {
            $training = Training::updateOrCreate(
                ['nama_training' => $tData['nama_training']],
                [
                    'slug' => Str::slug($tData['nama_training']),
                    'kategori' => $tData['kategori'],
                    'deskripsi' => $tData['deskripsi'],
                    'estimasi_waktu' => $tData['estimasi_waktu'],
                    'status' => 'published',
                    'urutan' => $index + 1,
                ]
            );

            // Seed Materials
            foreach ($tData['materials'] as $mIndex => $mData) {
                TrainingMaterial::updateOrCreate(
                    [
                        'training_id' => $training->id,
                        'judul_materi' => $mData['judul_materi'],
                    ],
                    [
                        'isi_materi' => $mData['isi_materi'],
                        'urutan' => $mIndex + 1,
                    ]
                );
            }

            // Seed Quizzes
            foreach ($tData['quizzes'] as $qIndex => $qData) {
                TrainingQuiz::updateOrCreate(
                    [
                        'training_id' => $training->id,
                        'pertanyaan' => $qData['pertanyaan'],
                    ],
                    [
                        'pilihan_jawaban' => $qData['pilihan_jawaban'],
                        'jawaban_benar' => $qData['jawaban_benar'],
                        'penjelasan' => $qData['penjelasan'] ?? null,
                        'urutan' => $qIndex + 1,
                    ]
                );
            }
        }

        // Seed Sample Progress for Sales 1 (Budi) and Sales 2 (Siti)
        $sales1 = User::where('email', 'sales1@simakis.com')->first();
        $sales2 = User::where('email', 'sales2@simakis.com')->first();

        $allTrainings = Training::with(['materials', 'quizzes'])->get();

        if ($sales1 && $allTrainings->count() >= 3) {
            // Training 1 Completed by Sales 1
            $t1 = $allTrainings[0];
            $mIds1 = $t1->materials->pluck('id')->toArray();
            UserTrainingProgress::updateOrCreate(
                ['user_id' => $sales1->id, 'training_id' => $t1->id],
                [
                    'completed_material_ids' => $mIds1,
                    'progress_persen' => 100,
                    'status' => 'selesai',
                    'last_material_id' => end($mIds1) ?: null,
                    'waktu_mulai' => now()->subDays(4),
                    'waktu_selesai' => now()->subDays(3),
                ]
            );
            UserQuizResult::updateOrCreate(
                ['user_id' => $sales1->id, 'training_id' => $t1->id],
                [
                    'nilai' => 100,
                    'jumlah_benar' => count($t1->quizzes),
                    'total_soal' => count($t1->quizzes),
                    'status_lulus' => true,
                    'waktu_selesai' => now()->subDays(3),
                ]
            );

            // Training 2 In Progress by Sales 1
            $t2 = $allTrainings[1];
            $mIds2 = $t2->materials->take(1)->pluck('id')->toArray();
            $prog2 = round((count($mIds2) / max(1, $t2->materials->count())) * 100);
            UserTrainingProgress::updateOrCreate(
                ['user_id' => $sales1->id, 'training_id' => $t2->id],
                [
                    'completed_material_ids' => $mIds2,
                    'progress_persen' => $prog2,
                    'status' => 'sedang_berjalan',
                    'last_material_id' => end($mIds2) ?: null,
                    'waktu_mulai' => now()->subDays(1),
                ]
            );

            // Training Sales Skill Completed by Sales 1
            $tSalesSkill = $allTrainings->where('kategori', 'sales_skill')->first();
            if ($tSalesSkill) {
                $mIdsSkill = $tSalesSkill->materials->pluck('id')->toArray();
                UserTrainingProgress::updateOrCreate(
                    ['user_id' => $sales1->id, 'training_id' => $tSalesSkill->id],
                    [
                        'completed_material_ids' => $mIdsSkill,
                        'progress_persen' => 100,
                        'status' => 'selesai',
                        'last_material_id' => end($mIdsSkill) ?: null,
                        'waktu_mulai' => now()->subDays(2),
                        'waktu_selesai' => now()->subDays(1),
                    ]
                );
                UserQuizResult::updateOrCreate(
                    ['user_id' => $sales1->id, 'training_id' => $tSalesSkill->id],
                    [
                        'nilai' => 100,
                        'jumlah_benar' => count($tSalesSkill->quizzes),
                        'total_soal' => count($tSalesSkill->quizzes),
                        'status_lulus' => true,
                        'waktu_selesai' => now()->subDays(1),
                    ]
                );
            }
        }

        if ($sales2 && $allTrainings->count() >= 2) {
            // Training 1 Completed by Sales 2
            $t1 = $allTrainings[0];
            $mIds1 = $t1->materials->pluck('id')->toArray();
            UserTrainingProgress::updateOrCreate(
                ['user_id' => $sales2->id, 'training_id' => $t1->id],
                [
                    'completed_material_ids' => $mIds1,
                    'progress_persen' => 100,
                    'status' => 'selesai',
                    'last_material_id' => end($mIds1) ?: null,
                    'waktu_mulai' => now()->subDays(3),
                    'waktu_selesai' => now()->subDays(2),
                ]
            );
            UserQuizResult::updateOrCreate(
                ['user_id' => $sales2->id, 'training_id' => $t1->id],
                [
                    'nilai' => 100,
                    'jumlah_benar' => count($t1->quizzes),
                    'total_soal' => count($t1->quizzes),
                    'status_lulus' => true,
                    'waktu_selesai' => now()->subDays(2),
                ]
            );
        }
    }
}
