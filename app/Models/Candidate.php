<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'selection_period_id',
        'registration_number',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'school_origin',
        'address',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Candidate $candidate) {
            $candidate->score()->firstOrCreate([
                'candidate_id' => $candidate->id,
            ], [
                'is_complete' => false,
                'needs_recalculation' => false,
            ]);
        });
    }

    public function period()
    {
        return $this->belongsTo(SelectionPeriod::class, 'selection_period_id');
    }

    public function score()
    {
        return $this->hasOne(CandidateScore::class);
    }

    public function calculationDetails()
    {
        return $this->hasMany(CalculationDetail::class);
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
