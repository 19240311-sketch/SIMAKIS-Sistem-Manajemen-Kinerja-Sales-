<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prospek';

    protected $fillable = [
        'user_id',
        'sepeda_motor_id',
        'sumber_prospek_id',
        'status_prospek_id',
        'nama_konsumen',
        'nomor_telepon',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'warna_motor_diminati',
        'harga_otr',
        'tanggal_follow_up_selanjutnya',
        'catatan',
        'tahap_data',
        'nomor_ktp',
        'tanggal_lahir',
        'pekerjaan',
        'skema_pembelian',
        'dp',
        'tenor_bulan',
        'angsuran_per_bulan',
        'leasing',
        'metode_pembayaran',
        'tanggal_deal',
        'is_demo',
    ];

    protected $casts = [
        'tanggal_follow_up_selanjutnya' => 'date',
        'tanggal_lahir' => 'date',
        'tanggal_deal' => 'date',
        'harga_otr' => 'integer',
        'dp' => 'integer',
        'tenor_bulan' => 'integer',
        'angsuran_per_bulan' => 'integer',
        'is_demo' => 'boolean',
    ];

    // Accessor aliases for backward and bilingual compatibility
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama_konsumen'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama_konsumen'] = $value;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->attributes['nomor_telepon'] ?? null;
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['nomor_telepon'] = $value;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->attributes['alamat'] ?? null;
    }

    public function setAddressAttribute($value): void
    {
        $this->attributes['alamat'] = $value;
    }

    public function getNotesAttribute(): ?string
    {
        return $this->attributes['catatan'] ?? null;
    }

    public function setNotesAttribute($value): void
    {
        $this->attributes['catatan'] = $value;
    }

    public function getMotorcycleIdAttribute(): ?int
    {
        return $this->attributes['sepeda_motor_id'] ?? null;
    }

    public function setMotorcycleIdAttribute($value): void
    {
        $this->attributes['sepeda_motor_id'] = $value;
    }

    public function getSourceIdAttribute(): ?int
    {
        return $this->attributes['sumber_prospek_id'] ?? null;
    }

    public function setSourceIdAttribute($value): void
    {
        $this->attributes['sumber_prospek_id'] = $value;
    }

    public function getProspectStatusIdAttribute(): ?int
    {
        return $this->attributes['status_prospek_id'] ?? null;
    }

    public function setProspectStatusIdAttribute($value): void
    {
        $this->attributes['status_prospek_id'] = $value;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class, 'sepeda_motor_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'sumber_prospek_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProspectStatus::class, 'status_prospek_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ProspectActivity::class, 'prospek_id')->orderBy('waktu_aktivitas', 'desc');
    }

    public function salesTransactions(): HasMany
    {
        return $this->hasMany(SalesTransaction::class, 'prospek_id');
    }

    public function latestActivity(): HasOne
    {
        return $this->hasOne(ProspectActivity::class, 'prospek_id')->latestOfMany('waktu_aktivitas');
    }

    /**
     * Get clean normalized WhatsApp phone number (starts with 62)
     */
    public function getWaNumberAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) ($this->nomor_telepon ?? $this->phone ?? ''));
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }
        return $phone;
    }

    /**
     * Generate customized follow-up WhatsApp message text
     */
    public function getWaDefaultMessageAttribute(): string
    {
        $salesName = auth()->user()?->nama ?? auth()->user()?->name ?? 'Sales Consultant';
        $motor = $this->motorcycle?->full_display_name ?? ($this->motorcycle?->nama_model ?? 'Sepeda Motor Yamaha');
        $konsumen = $this->nama_konsumen ?? $this->name ?? 'Bapak/Ibu';

        return "Halo Bapak/Ibu {$konsumen}, perkenalkan saya {$salesName} dari Dealer Resmi Yamaha JG Purwakarta.\n\n"
             . "Terkait ketertarikan Bapak/Ibu pada motor Yamaha {$motor}, apakah ada informasi promo, rincian diskon, atau simulasi kredit yang ingin kami bantu jelaskan hari ini?\n\n"
             . "Terima kasih, salam Semakin Di Depan!";
    }

    /**
     * Generate direct WhatsApp follow-up URL
     */
    public function getWaLinkAttribute(): string
    {
        $waNum = $this->wa_number;
        if (empty($waNum)) {
            return '#';
        }
        return "https://wa.me/{$waNum}?text=" . rawurlencode($this->wa_default_message);
    }

    /**
     * Get initials for avatar display.
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->nama_konsumen ?? $this->name ?? 'P';
        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Determine if this prospect requires follow-up (dormant for >= 3 days and not in final status).
     */
    public function getNeedsFollowUpAttribute(): bool
    {
        if ($this->status && ($this->status->status_akhir ?? $this->status->is_final)) {
            return false;
        }

        $lastDate = $this->latestActivity ? ($this->latestActivity->waktu_aktivitas ?? $this->latestActivity->activity_at) : $this->created_at;
        return $lastDate ? now()->diffInDays($lastDate) >= 3 : false;
    }

    /**
     * Get days since last interaction or creation.
     */
    public function getDaysSinceLastActivityAttribute(): int
    {
        $lastDate = $this->latestActivity ? ($this->latestActivity->waktu_aktivitas ?? $this->latestActivity->activity_at) : $this->created_at;
        return $lastDate ? (int) now()->diffInDays($lastDate) : 0;
    }
}
