<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_transactions';

    protected $fillable = [
        'nomor_transaksi',
        'user_id',
        'sepeda_motor_id',
        'warna_motor_id',
        'nama_warna',
        'jumlah_unit',
        'harga_satuan',
        'total_harga',
        'skema_pembelian',
        'leasing',
        'nama_konsumen',
        'nomor_telepon_konsumen',
        'alamat_konsumen',
        'prospek_id',
        'tanggal_transaksi',
        'status_transaksi',
        'catatan',
        'dibuat_oleh',
        'is_demo',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah_unit' => 'integer',
        'harga_satuan' => 'integer',
        'total_harga' => 'integer',
        'is_demo' => 'boolean',
    ];

    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class, 'sepeda_motor_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(MotorcycleColor::class, 'warna_motor_id');
    }

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class, 'prospek_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function scopeValid($query)
    {
        return $query->where('status_transaksi', 'valid');
    }

    public function scopeReal($query)
    {
        return $query->where('is_demo', false);
    }

    public function scopeDemo($query)
    {
        return $query->where('is_demo', true);
    }
}
