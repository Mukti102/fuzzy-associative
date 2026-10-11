<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'academic_score',
        'btq_score',
        'tahfidz_score',
        'interview_score',
        'is_complete',
        'needs_recalculation',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'academic_score' => 'decimal:2',
            'btq_score' => 'decimal:2',
            'tahfidz_score' => 'decimal:2',
            'interview_score' => 'decimal:2',
            'is_complete' => 'boolean',
            'needs_recalculation' => 'boolean',
        ];
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Check if all 4 required criteria are filled and within range 0 - 100.
     */
    public function checkCompletion(): bool
    {
        return !is_null($this->academic_score)
            && !is_null($this->btq_score)
            && !is_null($this->tahfidz_score)
            && !is_null($this->interview_score);
    }
}
