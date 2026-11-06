<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldFinding extends Model
{
    use HasFactory;

    protected $table = 'field_findings';

    protected $fillable = [
        'user_id',
        'tanggal_temuan',
        'nama_konsumen',
        'kontak_konsumen',
        'sepeda_motor_id',
        'warna_motor',
        'kisaran_anggaran',
        'metode_pembayaran',
        'status_minat',
        'kendala',
        'catatan_komunikasi',
        'rencana_tindak_lanjut',
        'tanggal_tindak_lanjut',
        'status_tindak_lanjut',
        'catatan_hasil_tindak_lanjut',
        'is_demo',
    ];

    protected $casts = [
        'tanggal_temuan' => 'date',
        'tanggal_tindak_lanjut' => 'date',
        'kisaran_anggaran' => 'integer',
        'is_demo' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class, 'sepeda_motor_id');
    }

    public function getStatusTindakLanjutLabelAttribute(): string
    {
        return match ($this->status_tindak_lanjut) {
            'menunggu' => 'Menunggu Tindak Lanjut',
            'sedang_diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai Ditindaklanjuti',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status_tindak_lanjut)),
        };
    }

    public function getStatusTindakLanjutBadgeAttribute(): string
    {
        return match ($this->status_tindak_lanjut) {
            'menunggu' => 'bg-amber-100 text-amber-800 border-amber-200',
            'sedang_diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'dibatalkan' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    public function getStatusMinatBadgeAttribute(): string
    {
        return match ($this->status_minat) {
            'tinggi' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'sedang' => 'bg-sky-100 text-sky-800 border-sky-200',
            'rendah' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
