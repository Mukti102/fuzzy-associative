<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'academic_membership_id',
        'btq_membership_id',
        'tahfidz_membership_id',
        'interview_membership_id',
        'output_membership_id',
        'weight',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function academicMembership()
    {
        return $this->belongsTo(FuzzyMembership::class, 'academic_membership_id');
    }

    public function btqMembership()
    {
        return $this->belongsTo(FuzzyMembership::class, 'btq_membership_id');
    }

    public function tahfidzMembership()
    {
        return $this->belongsTo(FuzzyMembership::class, 'tahfidz_membership_id');
    }

    public function interviewMembership()
    {
        return $this->belongsTo(FuzzyMembership::class, 'interview_membership_id');
    }

    public function outputMembership()
    {
        return $this->belongsTo(FuzzyMembership::class, 'output_membership_id');
    }
}
