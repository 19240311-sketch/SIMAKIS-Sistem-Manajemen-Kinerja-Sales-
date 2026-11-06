<?php

namespace App\Console\Commands;

use App\Models\FieldFinding;
use App\Models\Meeting;
use App\Models\MeetingAttendance;
use App\Models\PerformanceEvaluation;
use App\Models\Prospect;
use App\Models\RewardProgram;
use App\Models\SalesReward;
use App\Models\SalesTarget;
use App\Models\SalesTransaction;
use App\Models\User;
use Database\Seeders\SimakisDemoSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SimakisDemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simakis:demo 
                            {action=status : Action to perform: status, seed, clean}
                            {--force : Force cleanup without interactive confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kelola data demo presentasi SIMAKIS (status, seed, clean)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = strtolower($this->argument('action'));

        return match ($action) {
            'status' => $this->showStatus(),
            'seed' => $this->seedDemoData(),
            'clean' => $this->cleanDemoData(),
            default => $this->invalidAction($action),
        };
    }

    protected function showStatus(): int
    {
        $this->info('===========================================================');
        $this->info('  STATUS PEMISAHAN DATA SIMAKIS (DEMO vs DATA OPERASIONAL)');
        $this->info('===========================================================');

        $metrics = [
            ['Tabel', 'Data Asli / Operasional (is_demo=0)', 'Data Simulasi Demo (is_demo=1)', 'Total'],
            ['Pengguna / Sales', User::where('is_demo', false)->count(), User::where('is_demo', true)->count(), User::count()],
            ['Transaksi Penjualan', SalesTransaction::where('is_demo', false)->count(), SalesTransaction::where('is_demo', true)->count(), SalesTransaction::count()],
            ['Target Penjualan', SalesTarget::where('is_demo', false)->count(), SalesTarget::where('is_demo', true)->count(), SalesTarget::count()],
            ['Program Reward', RewardProgram::where('is_demo', false)->count(), RewardProgram::where('is_demo', true)->count(), RewardProgram::count()],
            ['Reward Sales', SalesReward::where('is_demo', false)->count(), SalesReward::where('is_demo', true)->count(), SalesReward::count()],
            ['Prospek Konsumen', Prospect::where('is_demo', false)->count(), Prospect::where('is_demo', true)->count(), Prospect::count()],
            ['Temuan Lapangan', FieldFinding::where('is_demo', false)->count(), FieldFinding::where('is_demo', true)->count(), FieldFinding::count()],
            ['Rapat Mingguan', Meeting::where('is_demo', false)->count(), Meeting::where('is_demo', true)->count(), Meeting::count()],
            ['Kehadiran Rapat', MeetingAttendance::where('is_demo', false)->count(), MeetingAttendance::where('is_demo', true)->count(), MeetingAttendance::count()],
            ['Evaluasi Kinerja', PerformanceEvaluation::where('is_demo', false)->count(), PerformanceEvaluation::where('is_demo', true)->count(), PerformanceEvaluation::count()],
        ];

        $this->table(array_shift($metrics), $metrics);

        $totalDemo = SalesTransaction::where('is_demo', true)->count() +
                     SalesTarget::where('is_demo', true)->count() +
                     Prospect::where('is_demo', true)->count() +
                     FieldFinding::where('is_demo', true)->count() +
                     Meeting::where('is_demo', true)->count() +
                     User::where('is_demo', true)->count();

        if ($totalDemo > 0) {
            $this->info("✓ Sistem saat ini memiliki {$totalDemo} entitas data simulasi aktif untuk keperluan presentasi.");
            $this->comment("Jalankan `php artisan simakis:demo clean` untuk membersihkan data simulasi sebelum diserahkan ke mitra.");
        } else {
            $this->info("✓ Sistem bersih. Tidak ada data simulasi demo di database (hanya data operasional asli).");
            $this->comment("Jalankan `php artisan simakis:demo seed` untuk menambahkan data simulasi presentasi.");
        }

        return Command::SUCCESS;
    }

    protected function seedDemoData(): int
    {
        $this->info('Mengisi data simulasi demo SIMAKIS...');
        $seeder = new SimakisDemoSeeder();
        $seeder->run();
        $this->info('✓ Data simulasi demo berhasil di-generate dengan penanda `is_demo = true`.');

        return $this->showStatus();
    }

    protected function cleanDemoData(): int
    {
        $demoTrxCount = SalesTransaction::where('is_demo', true)->count();
        $demoTargetCount = SalesTarget::where('is_demo', true)->count();
        $demoProspectCount = Prospect::where('is_demo', true)->count();
        $demoRewardCount = SalesReward::where('is_demo', true)->count();
        $demoProgramCount = RewardProgram::where('is_demo', true)->count();
        $demoFindingCount = FieldFinding::where('is_demo', true)->count();
        $demoAttendanceCount = MeetingAttendance::where('is_demo', true)->count();
        $demoMeetingCount = Meeting::where('is_demo', true)->count();
        $demoEvalCount = PerformanceEvaluation::where('is_demo', true)->count();
        $demoUserCount = User::where('is_demo', true)->count();

        $totalItems = $demoTrxCount + $demoTargetCount + $demoProspectCount + $demoRewardCount +
                      $demoProgramCount + $demoFindingCount + $demoAttendanceCount + $demoMeetingCount +
                      $demoEvalCount + $demoUserCount;

        if ($totalItems === 0) {
            $this->info('Tidak ada data simulasi demo yang ditemukan. Database sudah bersih.');
            return Command::SUCCESS;
        }

        $this->warn("DITEMUKAN {$totalItems} DATA SIMULASI DEMO YANG AKAN DIHAPUS:");
        $this->line("- Transaksi Penjualan Demo: {$demoTrxCount}");
        $this->line("- Target Penjualan Demo: {$demoTargetCount}");
        $this->line("- Prospek Konsumen Demo: {$demoProspectCount}");
        $this->line("- Sales Rewards Demo: {$demoRewardCount}");
        $this->line("- Reward Programs Demo: {$demoProgramCount}");
        $this->line("- Temuan Lapangan Demo: {$demoFindingCount}");
        $this->line("- Rapat & Kehadiran Demo: " . ($demoMeetingCount + $demoAttendanceCount));
        $this->line("- Evaluasi Kinerja Demo: {$demoEvalCount}");
        $this->line("- Akun Sales Simulasi Demo: {$demoUserCount}");
        $this->info("Semua akun asli (Supervisor, Budi Santoso, Siti Rahmawati) dan transaksi riil akan DIPERTAHANKAN 100%.");

        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda yakin ingin menghapus seluruh data simulasi demo di atas?', false)) {
                $this->info('Operasi pembersihan data demo dibatalkan.');
                return Command::SUCCESS;
            }
        }

        DB::transaction(function () {
            MeetingAttendance::where('is_demo', true)->delete();
            Meeting::where('is_demo', true)->delete();
            FieldFinding::where('is_demo', true)->delete();
            PerformanceEvaluation::where('is_demo', true)->delete();
            SalesReward::where('is_demo', true)->delete();
            RewardProgram::where('is_demo', true)->delete();
            SalesTransaction::where('is_demo', true)->forceDelete();
            Prospect::where('is_demo', true)->forceDelete();
            SalesTarget::where('is_demo', true)->delete();
            User::where('is_demo', true)->delete();
        });

        $this->info('✓ Seluruh data simulasi demo berhasil dibersihkan secara aman tanpa menyentuh data asli!');

        return Command::SUCCESS;
    }

    protected function invalidAction(string $action): int
    {
        $this->error("Aksi [{$action}] tidak valid. Gunakan: status, seed, atau clean.");
        return Command::FAILURE;
    }
}
