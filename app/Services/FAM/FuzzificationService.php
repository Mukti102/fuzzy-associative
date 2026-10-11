<?php

namespace App\Services\FAM;

use App\Models\FuzzyMembership;
use App\Models\FuzzyVariable;

class FuzzificationService
{
    /**
     * Calculate membership degree μ(x) for a given value and membership curve.
     */
    public function calculateDegree(float $value, FuzzyMembership $membership): float
    {
        $type = $membership->curve_type;
        $a = (float) $membership->param_a;
        $b = (float) $membership->param_b;
        $c = $membership->param_c !== null ? (float) $membership->param_c : null;
        $d = $membership->param_d !== null ? (float) $membership->param_d : null;

        return match ($type) {
            'shoulder_left' => $this->shoulderLeft($value, $a, $b),
            'triangular' => $this->triangular($value, $a, $b, (float) ($c ?? $b)),
            'trapezoidal' => $this->trapezoidal($value, $a, $b, (float) ($c ?? $b), (float) ($d ?? $c ?? $b)),
            'shoulder_right' => $this->shoulderRight($value, $a, $b),
            default => 0.0,
        };
    }

    /**
     * Shoulder Left (Kurva Trapesium Kiri):
     * μ(x) = 1 jika x <= a
     * μ(x) = (b - x) / (b - a) jika a < x < b
     * μ(x) = 0 jika x >= b
     */
    public function shoulderLeft(float $x, float $a, float $b): float
    {
        if ($x <= $a) {
            return 1.0;
        }
        if ($x >= $b) {
            return 0.0;
        }
        if ($b == $a) {
            return 0.0;
        }

        return max(0.0, min(1.0, round(($b - $x) / ($b - $a), 4)));
    }

    /**
     * Triangular (Kurva Segitiga):
     * μ(x) = 0 jika x <= a atau x >= c
     * μ(x) = (x - a) / (b - a) jika a < x <= b
     * μ(x) = (c - x) / (c - b) jika b < x < c
     */
    public function triangular(float $x, float $a, float $b, float $c): float
    {
        if ($x <= $a || $x >= $c) {
            return 0.0;
        }
        if ($x == $b) {
            return 1.0;
        }
        if ($x > $a && $x < $b) {
            return ($b == $a) ? 1.0 : max(0.0, min(1.0, round(($x - $a) / ($b - $a), 4)));
        }
        if ($x > $b && $x < $c) {
            return ($c == $b) ? 1.0 : max(0.0, min(1.0, round(($c - $x) / ($c - $b), 4)));
        }

        return 0.0;
    }

    /**
     * Trapezoidal (Kurva Trapesium Penuh):
     */
    public function trapezoidal(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x <= $a || $x >= $d) {
            return 0.0;
        }
        if ($x >= $b && $x <= $c) {
            return 1.0;
        }
        if ($x > $a && $x < $b) {
            return ($b == $a) ? 1.0 : max(0.0, min(1.0, round(($x - $a) / ($b - $a), 4)));
        }
        if ($x > $c && $x < $d) {
            return ($d == $c) ? 1.0 : max(0.0, min(1.0, round(($d - $x) / ($d - $c), 4)));
        }

        return 0.0;
    }

    /**
     * Shoulder Right (Kurva Trapesium Kanan):
     * μ(x) = 0 jika x <= a
     * μ(x) = (x - a) / (b - a) jika a < x < b
     * μ(x) = 1 jika x >= b
     */
    public function shoulderRight(float $x, float $a, float $b): float
    {
        if ($x <= $a) {
            return 0.0;
        }
        if ($x >= $b) {
            return 1.0;
        }
        if ($b == $a) {
            return 1.0;
        }

        return max(0.0, min(1.0, round(($x - $a) / ($b - $a), 4)));
    }

    /**
     * Fuzzify 4 criteria scores of a candidate.
     *
     * @param array<string, float> $scores ['AKADEMIK' => 85.0, 'BTQ' => 90.0, ...]
     * @return array<string, array<string, float>>
     */
    public function fuzzifyAll(array $scores): array
    {
        $variables = FuzzyVariable::where('type', 'input')
            ->with('memberships')
            ->get();

        $result = [];

        foreach ($variables as $variable) {
            $score = $scores[$variable->code] ?? 0.0;
            $result[$variable->code] = [
                'raw_score' => $score,
                'degrees' => [],
            ];

            foreach ($variable->memberships as $membership) {
                $degree = $this->calculateDegree($score, $membership);
                $result[$variable->code]['degrees'][$membership->name] = $degree;
            }
        }

        return $result;
    }
}
