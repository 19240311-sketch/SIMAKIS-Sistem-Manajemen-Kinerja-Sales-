<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTarget extends Model
{
    use HasFactory;

    protected $table = 'sales_targets';

    protected $fillable = [
        'user_id',
        'periode_tipe',
        'tahun',
        'bulan',
        'minggu_ke',
        'tanggal_mulai',
        'tanggal_selesai',
        'target_unit',
        'catatan',
        'dibuat_oleh',
        'is_demo',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'target_unit' => 'integer',
        'tahun' => 'integer',
        'bulan' => 'integer',
        'minggu_ke' => 'integer',
        'is_demo' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Get realisasi unit sold within target date range for this sales user
     */
    public function getRealisasiUnitAttribute(): int
    {
        return (int) SalesTransaction::valid()
            ->where('user_id', $this->user_id)
            ->whereBetween('tanggal_transaksi', [$this->tanggal_mulai, $this->tanggal_selesai])
            ->sum('jumlah_unit');
    }

    /**
     * Percentage achieved
     */
    public function getPersentaseCapaianAttribute(): float
    {
        if ($this->target_unit <= 0) {
            return $this->realisasi_unit > 0 ? 100.0 : 0.0;
        }

        return round(($this->realisasi_unit / $this->target_unit) * 100, 1);
    }

    /**
     * Remaining units needed
     */
    public function getSisaUnitAttribute(): int
    {
        $sisa = $this->target_unit - $this->realisasi_unit;
        return max(0, $sisa);
    }

    public function getStatusPencapaianAttribute(): string
    {
        if ($this->target_unit <= 0) {
            return 'Belum Ditetapkan';
        }
        return $this->realisasi_unit >= $this->target_unit ? 'Target Tercapai' : 'Belum Tercapai';
    }

    public function getPeriodeLabelAttribute(): string
    {
        if ($this->periode_tipe === 'mingguan' && $this->minggu_ke) {
            return "Minggu ke-{$this->minggu_ke} ({$this->bulan}/{$this->tahun})";
        }
        return "Bulan {$this->bulan}/{$this->tahun}";
    }
}
