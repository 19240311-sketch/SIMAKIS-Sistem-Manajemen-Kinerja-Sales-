<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table: activity_logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_nama')->nullable();
            $table->string('user_peran')->nullable();
            $table->string('jenis_aktivitas', 50); // login, logout, create, update, delete, status_change, role_change, export
            $table->string('modul', 50); // autentikasi, prospek, transaksi, target, reward, pengguna, pelatihan, tes_mingguan, rapat, laporan, supervisor
            $table->text('deskripsi');
            $table->string('record_type', 100)->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('data_sebelum')->nullable();
            $table->json('data_sesudah')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['modul', 'jenis_aktivitas']);
            $table->index(['user_id', 'created_at']);
        });

        // 2. Table: supervisor_histories
        Schema::create('supervisor_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('super_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('status_jabatan', 50)->default('aktif'); // aktif, digantikan, dinonaktifkan
            $table->string('alasan_pergantian')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status_jabatan']);
        });

        // 3. Table: supervisor_handovers
        Schema::create('supervisor_handovers', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_serah_terima', 50)->unique();
            $table->foreignId('supervisor_lama_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('supervisor_baru_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('super_admin_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_serah_terima');
            $table->string('status', 50)->default('menunggu_serah_terima'); // menunggu_serah_terima, dalam_proses, selesai
            $table->text('alasan_pergantian')->nullable();
            $table->json('ringkasan_penjualan')->nullable();
            $table->json('pencapaian_target')->nullable();
            $table->json('daftar_prospek_aktif')->nullable();
            $table->json('daftar_followup_pending')->nullable();
            $table->json('jadwal_rapat_mendatang')->nullable();
            $table->text('catatan_supervisor_lama')->nullable();
            $table->text('catatan_super_admin')->nullable();
            $table->timestamp('diselesaikan_pada')->nullable();
            $table->timestamps();

            $table->index(['supervisor_lama_id', 'supervisor_baru_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_handovers');
        Schema::dropIfExists('supervisor_histories');
        Schema::dropIfExists('activity_logs');
    }
};

