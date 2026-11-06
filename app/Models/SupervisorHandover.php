<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisorHandover extends Model
{
    use HasFactory;

    protected $table = 'supervisor_handovers';

    protected $fillable = [
        'nomor_serah_terima',
        'supervisor_lama_id',
        'supervisor_baru_id',
        'super_admin_id',
        'tanggal_serah_terima',
        'status', // 'menunggu_serah_terima', 'dalam_proses', 'selesai'
        'alasan_pergantian',
        'ringkasan_penjualan',
        'pencapaian_target',
        'daftar_prospek_aktif',
        'daftar_followup_pending',
        'jadwal_rapat_mendatang',
        'catatan_supervisor_lama',
        'catatan_super_admin',
        'diselesaikan_pada',
    ];

    protected $casts = [
        'tanggal_serah_terima' => 'date',
        'diselesaikan_pada' => 'datetime',
        'ringkasan_penjualan' => 'array',
        'pencapaian_target' => 'array',
        'daftar_prospek_aktif' => 'array',
        'daftar_followup_pending' => 'array',
        'jadwal_rapat_mendatang' => 'array',
    ];

    public function supervisorLama(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_lama_id');
    }

    public function supervisorBaru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_baru_id');
    }

    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'selesai' => [
                'label' => 'Selesai',
                'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'badge' => 'bg-emerald-100 text-emerald-800',
                'icon' => 'check-circle',
            ],
            'dalam_proses' => [
                'label' => 'Dalam Proses',
                'bg' => 'bg-amber-50 text-amber-700 border-amber-200',
                'badge' => 'bg-amber-100 text-amber-800',
                'icon' => 'clock',
            ],
            'menunggu_serah_terima' => [
                'label' => 'Menunggu Serah Terima',
                'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
                'badge' => 'bg-blue-100 text-blue-800',
                'icon' => 'hourglass',
            ],
            default => [
                'label' => ucfirst($this->status),
                'bg' => 'bg-slate-50 text-slate-700 border-slate-200',
                'badge' => 'bg-slate-100 text-slate-800',
                'icon' => 'info',
            ],
        };
    }
}

