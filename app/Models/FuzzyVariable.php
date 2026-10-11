<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuzzyVariable extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'min_range',
        'max_range',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'min_range' => 'decimal:2',
            'max_range' => 'decimal:2',
        ];
    }

    public function memberships()
    {
        return $this->hasMany(FuzzyMembership::class);
    }
}
