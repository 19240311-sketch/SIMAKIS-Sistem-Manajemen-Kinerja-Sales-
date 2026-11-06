<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false; // only created_at is used

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_nama',
        'user_peran',
        'jenis_aktivitas',
        'modul',
        'deskripsi',
        'record_type',
        'record_id',
        'data_sebelum',
        'data_sesudah',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes for flexible filtering
    public function scopeModule($query, $module)
    {
        if (!empty($module)) {
            return $query->where('modul', $module);
        }
        return $query;
    }

    public function scopeActivityType($query, $type)
    {
        if (!empty($type)) {
            return $query->where('jenis_aktivitas', $type);
        }
        return $query;
    }

    public function scopeForRole($query, $role)
    {
        if (!empty($role)) {
            return $query->where('user_peran', $role);
        }
        return $query;
    }

    public function scopeForUser($query, $userId)
    {
        if (!empty($userId)) {
            return $query->where('user_id', $userId);
        }
        return $query;
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('user_nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('modul', 'like', "%{$search}%")
                  ->orWhere('jenis_aktivitas', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope to limit logs to operational sales activities and the supervisor's own activities
     */
    public function scopeOperationalSales($query, $supervisorUserId = null)
    {
        return $query->where(function ($q) use ($supervisorUserId) {
            // 1. Aktivitas oleh tim sales
            $q->where('user_peran', 'sales')
            // 2. Atau aktivitas modul operasional
              ->orWhereIn('modul', ['prospek', 'transaksi', 'target', 'reward', 'laporan', 'pelatihan', 'tes_mingguan', 'rapat', 'temuan_lapangan']);

            // 3. Atau aktivitas oleh Supervisor itu sendiri (login, logout, evaluasi, dll)
            if ($supervisorUserId) {
                $q->orWhere('user_id', $supervisorUserId);
            }
        })->whereNotIn('modul', ['pengguna', 'supervisor', 'keamanan_super_admin']);
    }
}

