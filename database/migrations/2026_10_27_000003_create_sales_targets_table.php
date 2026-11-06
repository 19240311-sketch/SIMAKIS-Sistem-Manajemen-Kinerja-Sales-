<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Sales
            $table->enum('periode_tipe', ['bulanan', 'mingguan', 'khusus'])->default('bulanan');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('bulan')->nullable(); // 1-12
            $table->unsignedTinyInteger('minggu_ke')->nullable(); // 1-5
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedInteger('target_unit')->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'tahun', 'bulan']);
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
