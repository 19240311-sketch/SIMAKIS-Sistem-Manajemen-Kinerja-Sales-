<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Sales
            $table->date('tanggal_temuan');
            $table->string('nama_konsumen', 150)->nullable();
            $table->string('kontak_konsumen', 50)->nullable();
            $table->foreignId('sepeda_motor_id')->nullable()->constrained('sepeda_motor')->nullOnDelete();
            $table->string('warna_motor', 100)->nullable();
            $table->unsignedBigInteger('kisaran_anggaran')->nullable();
            $table->enum('metode_pembayaran', ['cash', 'kredit', 'belum_pasti'])->default('belum_pasti');
            $table->enum('status_minat', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->text('kendala')->nullable(); // misal: ketersediaan warna belum ada, inden lama, pertimbangan leasing
            $table->text('catatan_komunikasi');
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->date('tanggal_tindak_lanjut')->nullable();
            $table->enum('status_tindak_lanjut', [
                'menunggu',
                'sedang_diproses',
                'selesai',
                'dibatalkan'
            ])->default('menunggu');
            $table->text('catatan_hasil_tindak_lanjut')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status_tindak_lanjut']);
            $table->index('tanggal_temuan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_findings');
    }
};
