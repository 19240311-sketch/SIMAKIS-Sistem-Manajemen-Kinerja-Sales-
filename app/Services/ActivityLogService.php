<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    private static array $sensitiveFields = [
        'password',
        'password_confirmation',
        'remember_token',
        'current_password',
        'new_password',
        'new_password_confirmation',
        'token',
        '_token',
    ];

    /**
     * Record a system activity log safely.
     *
     * @param string $activityType (login, logout, tambah, ubah, hapus, status, ganti_jabatan, ekspor, dll)
     * @param string $module (autentikasi, prospek, transaksi, target, reward, pengguna, pelatihan, tes_mingguan, rapat, laporan, serah_terima, supervisor)
     * @param string $description Human readable description of what happened
     * @param mixed $record The affected Eloquent model or null
     * @param array|null $beforeData Snapshot before changes
     * @param array|null $afterData Snapshot after changes
     * @param mixed $overrideUser Explicit User model or null (falls back to Auth::user())
     * @return ActivityLog|null
     */
    public static function log(
        string $activityType,
        string $module,
        string $description,
        mixed $record = null,
        ?array $beforeData = null,
        ?array $afterData = null,
        mixed $overrideUser = null
    ): ?ActivityLog {
        try {
            $user = $overrideUser ?? Auth::user();

            $recordType = null;
            $recordId = null;

            if ($record && is_object($record)) {
                $recordType = get_class($record);
                $recordId = $record->id ?? null;
            }

            $cleanBefore = $beforeData ? self::sanitizeData($beforeData) : null;
            $cleanAfter = $afterData ? self::sanitizeData($afterData) : null;

            return ActivityLog::create([
                'user_id' => $user?->id,
                'user_nama' => $user?->nama ?? $user?->name ?? 'Sistem / Tamu',
                'user_peran' => $user?->peran ?? $user?->role ?? 'guest',
                'jenis_aktivitas' => $activityType,
                'modul' => $module,
                'deskripsi' => $description,
                'record_type' => $recordType,
                'record_id' => $recordId,
                'data_sebelum' => $cleanBefore,
                'data_sesudah' => $cleanAfter,
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => substr(Request::userAgent() ?? 'CLI/Browser', 0, 255),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Fail-safe: Never disrupt core user operations if logging encounters an issue
            Log::error('Gagal mencatat ActivityLog: ' . $e->getMessage(), [
                'type' => $activityType,
                'module' => $module,
                'error' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Specialized helper for authentication logs (login, logout, failed login)
     */
    public static function logAuth(string $type, string $description, ?object $user = null): ?ActivityLog
    {
        return self::log(
            activityType: $type,
            module: 'autentikasi',
            description: $description,
            overrideUser: $user
        );
    }

    /**
     * Recursively strip out passwords and sensitive fields from logging payloads
     */
    public static function sanitizeData(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), self::$sensitiveFields, true)) {
                $sanitized[$key] = '********';
            } elseif (is_array($value)) {
                $sanitized[$key] = self::sanitizeData($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
}

