<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('performance_evaluations');
        Schema::dropIfExists('performance_criteria');

        Schema::create('performance_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 50); // Sangat Baik, Baik, Cukup, Perlu Evaluasi
            $table->decimal('persentase_min', 6, 2)->default(0);
            $table->decimal('persentase_max', 6, 2)->nullable();
            $table->string('deskripsi', 255)->nullable();
            $table->string('warna_badge', 30)->default('blue');
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->timestamps();
        });

        // Seed initial default criteria
        DB::table('performance_criteria')->insert([
            [
                'kategori' => 'Sangat Baik',
                'persentase_min' => 120.00,
                'persentase_max' => null,
                'deskripsi' => 'Pencapaian target minimal 120%',
                'warna_badge' => 'emerald',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Baik',
                'persentase_min' => 100.00,
                'persentase_max' => 119.99,
                'deskripsi' => 'Pencapaian target 100% sampai kurang dari 120%',
                'warna_badge' => 'blue',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Cukup',
                'persentase_min' => 75.00,
                'persentase_max' => 99.99,
                'deskripsi' => 'Pencapaian target 75% sampai kurang dari 100%',
                'warna_badge' => 'amber',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Perlu Evaluasi',
                'persentase_min' => 0.00,
                'persentase_max' => 74.99,
                'deskripsi' => 'Pencapaian target di bawah 75%',
                'warna_badge' => 'rose',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Sales
            $table->foreignId('supervisor_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('periode_bulan');
            $table->unsignedSmallInteger('periode_tahun');
            $table->unsignedInteger('target_unit')->default(0);
            $table->unsignedInteger('realisasi_unit')->default(0);
            $table->decimal('persentase_capaian', 6, 2)->default(0);
            $table->unsignedInteger('peringkat')->default(1);
            $table->string('status_kinerja', 50)->default('Perlu Evaluasi');
            $table->text('catatan_evaluasi')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'periode_tahun', 'periode_bulan'], 'unq_pe_user_yr_mo');
            $table->index(['periode_tahun', 'periode_bulan'], 'idx_pe_yr_mo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_evaluations');
        Schema::dropIfExists('performance_criteria');
    }
};
