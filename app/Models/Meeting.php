<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meetings';

    protected $fillable = [
        'judul_rapat',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'agenda',
        'deskripsi',
        'metode',
        'link_zoom',
        'nama_tempat',
        'alamat_lokasi',
        'catatan_peserta',
        'status',
        'notulen',
        'hasil_evaluasi',
        'rencana_tindak_lanjut',
        'penanggung_jawab_tindak_lanjut',
        'batas_waktu_tindak_lanjut',
        'status_tindak_lanjut',
        'dibuat_oleh',
        'is_demo',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'batas_waktu_tindak_lanjut' => 'date',
        'is_demo' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(MeetingAttendance::class, 'meeting_id');
    }

    public function getMetodeLabelAttribute(): string
    {
        return match ($this->metode) {
            'online' => 'Online (Zoom Meeting)',
            'offline' => 'Offline (Tatap Muka di Kantor)',
            'hybrid' => 'Hybrid (Online Zoom & Offline Kantor)',
            default => ucfirst($this->metode),
        };
    }

    public function getMetodeBadgeAttribute(): string
    {
        return match ($this->metode) {
            'online' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'offline' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'hybrid' => 'bg-amber-100 text-amber-800 border-amber-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dijadwalkan' => 'Dijadwalkan',
            'berlangsung' => 'Sedang Berlangsung',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'dijadwalkan' => 'bg-blue-100 text-blue-800 border-blue-200',
            'berlangsung' => 'bg-amber-100 text-amber-800 border-amber-200',
            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'dibatalkan' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}
