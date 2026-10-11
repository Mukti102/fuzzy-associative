<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectionPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year',
        'start_date',
        'end_date',
        'is_active',
        'passing_grade',
        'quota',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
            'passing_grade' => 'decimal:4',
            'quota' => 'integer',
        ];
    }

    /**
     * Scope to find the active period.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function calculationRuns()
    {
        return $this->hasMany(CalculationRun::class);
    }
}
