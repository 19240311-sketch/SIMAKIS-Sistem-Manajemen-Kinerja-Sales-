<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('judul_rapat', 200);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->text('agenda');
            $table->text('deskripsi')->nullable();
            $table->enum('metode', ['online', 'offline', 'hybrid'])->default('hybrid');
            $table->string('link_zoom', 500)->nullable();
            $table->string('nama_tempat', 150)->nullable();
            $table->text('alamat_lokasi')->nullable();
            $table->text('catatan_peserta')->nullable();
            $table->enum('status', ['dijadwalkan', 'berlangsung', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            
            // Notulen dan evaluasi rapat
            $table->longText('notulen')->nullable();
            $table->text('hasil_evaluasi')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->string('penanggung_jawab_tindak_lanjut', 150)->nullable();
            $table->date('batas_waktu_tindak_lanjut')->nullable();
            $table->enum('status_tindak_lanjut', ['belum_selesai', 'dalam_proses', 'selesai'])->default('belum_selesai');

            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['tanggal', 'status']);
        });

        Schema::create('meeting_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('meetings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Sales
            $table->enum('status_undangan', ['diundang'])->default('diundang');
            $table->enum('konfirmasi_kehadiran', [
                'belum_konfirmasi',
                'hadir_online',
                'hadir_offline',
                'izin',
                'tidak_hadir'
            ])->default('belum_konfirmasi');
            $table->enum('status_kehadiran', [
                'belum_dicatat',
                'hadir',
                'tidak_hadir',
                'izin'
            ])->default('belum_dicatat');
            $table->text('catatan_kehadiran')->nullable();
            $table->timestamp('waktu_konfirmasi')->nullable();
            $table->timestamps();

            $table->unique(['meeting_id', 'user_id']);
            $table->index(['user_id', 'status_kehadiran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_attendances');
        Schema::dropIfExists('meetings');
    }
};
