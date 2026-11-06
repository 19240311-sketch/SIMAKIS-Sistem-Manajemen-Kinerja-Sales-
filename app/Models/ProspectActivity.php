<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectActivity extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_prospek';

    protected $fillable = [
        'prospek_id',
        'user_id',
        'status_prospek_id',
        'jenis_aktivitas',
        'waktu_aktivitas',
        'catatan',
        'is_demo',
    ];

    protected $casts = [
        'waktu_aktivitas' => 'datetime',
        'is_demo' => 'boolean',
    ];

    // Accessor aliases
    public function getTypeAttribute(): ?string
    {
        return $this->attributes['jenis_aktivitas'] ?? null;
    }

    public function setTypeAttribute($value): void
    {
        $this->attributes['jenis_aktivitas'] = $value;
    }

    public function getActivityAtAttribute()
    {
        return $this->waktu_aktivitas;
    }

    public function setActivityAtAttribute($value): void
    {
        $this->attributes['waktu_aktivitas'] = $value;
    }

    public function getNotesAttribute(): ?string
    {
        return $this->attributes['catatan'] ?? null;
    }

    public function setNotesAttribute($value): void
    {
        $this->attributes['catatan'] = $value;
    }

    public function getProspectIdAttribute(): ?int
    {
        return $this->attributes['prospek_id'] ?? null;
    }

    public function setProspectIdAttribute($value): void
    {
        $this->attributes['prospek_id'] = $value;
    }

    public function getProspectStatusIdAttribute(): ?int
    {
        return $this->attributes['status_prospek_id'] ?? null;
    }

    public function setProspectStatusIdAttribute($value): void
    {
        $this->attributes['status_prospek_id'] = $value;
    }

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class, 'prospek_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProspectStatus::class, 'status_prospek_id');
    }
}
