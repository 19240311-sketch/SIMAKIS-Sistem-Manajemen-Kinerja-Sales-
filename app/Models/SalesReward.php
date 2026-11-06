<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesReward extends Model
{
    use HasFactory;

    protected $table = 'sales_rewards';

    protected $fillable = [
        'reward_program_id',
        'user_id',
        'periode_bulan',
        'periode_tahun',
        'realisasi_unit',
        'status',
        'tanggal_verifikasi',
        'diverifikasi_oleh',
        'tanggal_pemberian',
        'catatan',
        'is_demo',
    ];

    protected $casts = [
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
        'realisasi_unit' => 'integer',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_pemberian' => 'datetime',
        'is_demo' => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(RewardProgram::class, 'reward_program_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'belum_tercapai' => 'Belum Tercapai',
            'target_tercapai' => 'Target Tercapai',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'disetujui' => 'Disetujui',
            'hadiah_diberikan' => 'Hadiah Diberikan',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'belum_tercapai' => 'bg-slate-100 text-slate-700 border-slate-200',
            'target_tercapai' => 'bg-blue-100 text-blue-700 border-blue-200',
            'menunggu_verifikasi' => 'bg-amber-100 text-amber-800 border-amber-200',
            'disetujui' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'hadiah_diberikan' => 'bg-purple-100 text-purple-800 border-purple-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
