<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuzzyMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'fuzzy_variable_id',
        'name',
        'curve_type',
        'param_a',
        'param_b',
        'param_c',
        'param_d',
    ];

    protected function casts(): array
    {
        return [
            'param_a' => 'decimal:4',
            'param_b' => 'decimal:4',
            'param_c' => 'decimal:4',
            'param_d' => 'decimal:4',
        ];
    }

    public function variable()
    {
        return $this->belongsTo(FuzzyVariable::class, 'fuzzy_variable_id');
    }
}
