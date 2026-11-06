<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingAttendance extends Model
{
    use HasFactory;

    protected $table = 'meeting_attendances';

    protected $fillable = [
        'meeting_id',
        'user_id',
        'status_undangan',
        'konfirmasi_kehadiran',
        'status_kehadiran',
        'catatan_kehadiran',
        'waktu_konfirmasi',
        'is_demo',
    ];

    protected $casts = [
        'waktu_konfirmasi' => 'datetime',
        'is_demo' => 'boolean',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getKonfirmasiLabelAttribute(): string
    {
        return match ($this->konfirmasi_kehadiran) {
            'belum_konfirmasi' => 'Belum Konfirmasi',
            'hadir_online' => 'Hadir via Zoom',
            'hadir_offline' => 'Hadir di Kantor',
            'izin' => 'Izin / Tidak Bisa Hadir',
            'tidak_hadir' => 'Tidak Hadir',
            default => ucfirst(str_replace('_', ' ', $this->konfirmasi_kehadiran)),
        };
    }

    public function getStatusKehadiranLabelAttribute(): string
    {
        return match ($this->status_kehadiran) {
            'belum_dicatat' => 'Belum Dicatat',
            'hadir' => 'Hadir (Terverifikasi)',
            'tidak_hadir' => 'Tidak Hadir (Alpa)',
            'izin' => 'Izin Resmi',
            default => ucfirst(str_replace('_', ' ', $this->status_kehadiran)),
        };
    }
}
