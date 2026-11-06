<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceEvaluation extends Model
{
    use HasFactory;

    protected $table = 'performance_evaluations';

    protected $fillable = [
        'user_id',
        'supervisor_id',
        'periode_bulan',
        'periode_tahun',
        'target_unit',
        'realisasi_unit',
        'persentase_capaian',
        'peringkat',
        'status_kinerja',
        'catatan_evaluasi',
        'rekomendasi',
        'rencana_tindak_lanjut',
        'is_demo',
    ];

    protected $casts = [
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
        'target_unit' => 'integer',
        'realisasi_unit' => 'integer',
        'persentase_capaian' => 'float',
        'peringkat' => 'integer',
        'is_demo' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->status_kinerja) {
            'Sangat Baik' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'Baik' => 'bg-blue-50 text-[#0A4DF3] border border-blue-200',
            'Cukup' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'Perlu Evaluasi' => 'bg-rose-50 text-rose-700 border border-rose-200',
            default => 'bg-slate-50 text-slate-700 border border-slate-200',
        };
    }
}
