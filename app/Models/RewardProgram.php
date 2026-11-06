<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RewardProgram extends Model
{
    use HasFactory;

    protected $table = 'reward_programs';

    protected $fillable = [
        'nama_program',
        'deskripsi',
        'target_minimal_unit',
        'periode_bulan',
        'periode_tahun',
        'jenis_hadiah',
        'nominal_hadiah',
        'ketentuan',
        'is_active',
        'dibuat_oleh',
        'is_demo',
    ];

    protected $casts = [
        'target_minimal_unit' => 'integer',
        'nominal_hadiah' => 'integer',
        'is_active' => 'boolean',
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
        'is_demo' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function salesRewards(): HasMany
    {
        return $this->hasMany(SalesReward::class, 'reward_program_id');
    }

    public function getJenisHadiahLabelAttribute(): string
    {
        return match ($this->jenis_hadiah) {
            'bonus_insentif' => 'Bonus Insentif Uang',
            'voucher_belanja' => 'Voucher Belanja',
            'merchandise' => 'Merchandise Eksklusif',
            'penghargaan_sales_terbaik' => 'Penghargaan Sales Terbaik',
            default => ucfirst(str_replace('_', ' ', $this->jenis_hadiah)),
        };
    }
}
