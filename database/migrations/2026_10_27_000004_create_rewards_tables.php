<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_programs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program', 150);
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('target_minimal_unit')->default(15);
            $table->unsignedTinyInteger('periode_bulan')->nullable(); // 1-12
            $table->unsignedSmallInteger('periode_tahun')->nullable();
            $table->enum('jenis_hadiah', ['bonus_insentif', 'voucher_belanja', 'merchandise', 'penghargaan_sales_terbaik'])->default('bonus_insentif');
            $table->unsignedBigInteger('nominal_hadiah')->default(0);
            $table->text('ketentuan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('sales_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reward_program_id')->constrained('reward_programs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Sales
            $table->unsignedTinyInteger('periode_bulan');
            $table->unsignedSmallInteger('periode_tahun');
            $table->unsignedInteger('realisasi_unit')->default(0);
            $table->enum('status', [
                'belum_tercapai',
                'target_tercapai',
                'menunggu_verifikasi',
                'disetujui',
                'hadiah_diberikan'
            ])->default('belum_tercapai');
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_pemberian')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'periode_tahun', 'periode_bulan']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_rewards');
        Schema::dropIfExists('reward_programs');
    }
};
