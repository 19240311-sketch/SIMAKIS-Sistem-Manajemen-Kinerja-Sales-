<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_SUPERVISOR = 'supervisor';
    const ROLE_SALES = 'sales';

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'foto_profil',
        'cabang',
        'password',
        'peran',
        'kode_sales',
        'status_aktif',
        'is_demo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'is_demo' => 'boolean',
        'password' => 'hashed',
    ];

    // Accessor aliases for name, role, is_active
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama'] = $value;
    }

    public function getRoleAttribute(): ?string
    {
        return $this->attributes['peran'] ?? null;
    }

    public function setRoleAttribute($value): void
    {
        $this->attributes['peran'] = $value;
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) ($this->attributes['status_aktif'] ?? false);
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status_aktif'] = $value;
    }

    public function getAvatarInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->nama ?? ''));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->nama ?? 'US', 0, 2));
    }

    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto_profil && file_exists(public_path('storage/' . $this->foto_profil))) {
            return asset('storage/' . $this->foto_profil);
        }
        return null;
    }

    public function isSuperAdmin(): bool
    {
        return ($this->peran ?? $this->role) === self::ROLE_SUPER_ADMIN;
    }

    public function isSupervisor(): bool
    {
        return in_array($this->peran ?? $this->role, [self::ROLE_SUPERVISOR, 'admin']);
    }

    public function isStrictSupervisor(): bool
    {
        return ($this->peran ?? $this->role) === self::ROLE_SUPERVISOR;
    }

    public function isAdmin(): bool
    {
        return $this->isSupervisor();
    }

    public function isSales(): bool
    {
        return ($this->peran ?? $this->role) === self::ROLE_SALES;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->peran ?? $this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_SUPERVISOR => 'Supervisor',
            self::ROLE_SALES => 'Sales',
            default => ucfirst($this->peran ?? 'Pengguna'),
        };
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->peran ?? $this->role) {
            self::ROLE_SUPER_ADMIN => 'bg-purple-100 text-purple-800 border-purple-200',
            self::ROLE_SUPERVISOR => 'bg-blue-100 text-blue-800 border-blue-200',
            self::ROLE_SALES => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function prospects(): HasMany
    {
        return $this->hasMany(Prospect::class, 'user_id');
    }

    public function prospectActivities(): HasMany
    {
        return $this->hasMany(ProspectActivity::class, 'user_id');
    }

    public function trainingProgresses(): HasMany
    {
        return $this->hasMany(UserTrainingProgress::class, 'user_id');
    }

    public function quizResults(): HasMany
    {
        return $this->hasMany(UserQuizResult::class, 'user_id');
    }

    public function weeklyAttempts(): HasMany
    {
        return $this->hasMany(WeeklyAttempt::class, 'user_id');
    }

    public function salesTransactions(): HasMany
    {
        return $this->hasMany(SalesTransaction::class, 'user_id');
    }

    public function salesTargets(): HasMany
    {
        return $this->hasMany(SalesTarget::class, 'user_id');
    }

    public function salesRewards(): HasMany
    {
        return $this->hasMany(SalesReward::class, 'user_id');
    }

    public function fieldFindings(): HasMany
    {
        return $this->hasMany(FieldFinding::class, 'user_id');
    }

    public function performanceEvaluations(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'user_id');
    }

    public function meetingAttendances(): HasMany
    {
        return $this->hasMany(MeetingAttendance::class, 'user_id');
    }
}
