<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sales_transactions');

        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Sales
            $table->foreignId('sepeda_motor_id')->constrained('sepeda_motor')->restrictOnDelete();
            $table->foreignId('warna_motor_id')->nullable()->constrained('warna_motor')->nullOnDelete();
            $table->string('nama_warna', 100)->nullable();
            $table->unsignedInteger('jumlah_unit')->default(1);
            $table->unsignedBigInteger('harga_satuan')->default(0);
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->enum('skema_pembelian', ['cash', 'kredit'])->default('cash');
            $table->string('leasing', 100)->nullable();
            $table->string('nama_konsumen', 150);
            $table->string('nomor_telepon_konsumen', 30)->nullable();
            $table->text('alamat_konsumen')->nullable();
            $table->foreignId('prospek_id')->nullable()->constrained('prospek')->nullOnDelete();
            $table->date('tanggal_transaksi');
            $table->enum('status_transaksi', ['valid', 'batal'])->default('valid');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'tanggal_transaksi', 'status_transaksi'], 'idx_st_user_date_status');
            $table->index('sepeda_motor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_transactions');
    }
};
