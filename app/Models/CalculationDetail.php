<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'calculation_run_id',
        'candidate_id',
        'fuzzification_data',
        'activated_rules_data',
        'fam_matrix_data',
        'composition_data',
        'defuzzification_data',
        'final_score',
        'decision',
        'rank',
        'is_outdated',
    ];

    protected function casts(): array
    {
        return [
            'fuzzification_data' => 'array',
            'activated_rules_data' => 'array',
            'fam_matrix_data' => 'array',
            'composition_data' => 'array',
            'defuzzification_data' => 'array',
            'final_score' => 'decimal:4',
            'rank' => 'integer',
            'is_outdated' => 'boolean',
        ];
    }

    public function calculationRun()
    {
        return $this->belongsTo(CalculationRun::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
