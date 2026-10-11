<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculationRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'selection_period_id',
        'run_by_user_id',
        'run_at',
        'encoding_method',
        'composition_method',
        'defuzzification_method',
        'config_snapshot',
        'total_candidates',
        'passed_candidates',
        'failed_candidates',
    ];

    protected function casts(): array
    {
        return [
            'run_at' => 'datetime',
            'config_snapshot' => 'array',
            'total_candidates' => 'integer',
            'passed_candidates' => 'integer',
            'failed_candidates' => 'integer',
        ];
    }

    public function period()
    {
        return $this->belongsTo(SelectionPeriod::class, 'selection_period_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'run_by_user_id');
    }

    public function details()
    {
        return $this->hasMany(CalculationDetail::class)->orderBy('rank');
    }
}
