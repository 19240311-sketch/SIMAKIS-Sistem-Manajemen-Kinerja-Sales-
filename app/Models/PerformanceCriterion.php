<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceCriterion extends Model
{
    use HasFactory;

    protected $table = 'performance_criteria';

    protected $fillable = [
        'kategori',
        'persentase_min',
        'persentase_max',
        'deskripsi',
        'warna_badge',
        'urutan',
    ];

    protected $casts = [
        'persentase_min' => 'float',
        'persentase_max' => 'float',
        'urutan' => 'integer',
    ];

    public static function determineCategory(float $percentage): string
    {
        $criteria = static::orderBy('persentase_min', 'desc')->get();

        foreach ($criteria as $criterion) {
            $minMet = $percentage >= $criterion->persentase_min;
            $maxMet = is_null($criterion->persentase_max) || $percentage <= $criterion->persentase_max;

            if ($minMet && $maxMet) {
                return $criterion->kategori;
            }
        }

        return 'Perlu Evaluasi';
    }

    public static function getBadgeColor(string $category): string
    {
        return match ($category) {
            'Sangat Baik' => 'emerald',
            'Baik' => 'blue',
            'Cukup' => 'amber',
            'Perlu Evaluasi' => 'rose',
            default => 'slate',
        };
    }
}
