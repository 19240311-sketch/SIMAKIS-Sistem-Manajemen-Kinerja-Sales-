<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisorHistory extends Model
{
    use HasFactory;

    protected $table = 'supervisor_histories';

    protected $fillable = [
        'user_id',
        'super_admin_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_jabatan', // 'aktif', 'digantikan', 'dinonaktifkan'
        'alasan_pergantian',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status_jabatan) {
            'aktif' => [
                'label' => 'Sedang Menjabat',
                'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'dot' => 'bg-emerald-500',
            ],
            'digantikan' => [
                'label' => 'Selesai / Digantikan',
                'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
                'dot' => 'bg-blue-500',
            ],
            'dinonaktifkan' => [
                'label' => 'Dinonaktifkan',
                'bg' => 'bg-red-50 text-red-700 border-red-200',
                'dot' => 'bg-red-500',
            ],
            default => [
                'label' => ucfirst($this->status_jabatan),
                'bg' => 'bg-slate-50 text-slate-700 border-slate-200',
                'dot' => 'bg-slate-500',
            ],
        };
    }
}

