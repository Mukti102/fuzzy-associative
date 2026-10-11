<?php

namespace App\Services\FAM;

class DefuzzificationService
{
    /**
     * Compute defuzzified crisp score.
     *
     * @param array<int, array<string, mixed>> $evaluatedRules
     * @param array<string, mixed> $inferenceData
     * @param string $defuzzificationMethod 'weighted-average' | 'winner-take-all' | 'centroid'
     * @return array<string, mixed>
     */
    public function defuzzify(
        array $evaluatedRules,
        array $inferenceData,
        string $defuzzificationMethod = 'weighted-average'
    ): array {
        return match ($defuzzificationMethod) {
            'winner-take-all' => $this->winnerTakeAll($evaluatedRules),
            'centroid' => $this->centroid($inferenceData),
            default => $this->weightedAverage($evaluatedRules),
        };
    }

    /**
     * Weighted Average Method:
     * z* = sum(alpha_k * w_k * y_bar_k) / sum(alpha_k * w_k)
     */
    public function weightedAverage(array $evaluatedRules): array
    {
        $numerator = 0.0;
        $denominator = 0.0;
        $steps = [];

        foreach ($evaluatedRules as $rule) {
            if ($rule['firing_strength'] <= 0) {
                continue;
            }

            $weight = $rule['weight'] ?? 1.0;
            $effectiveAlpha = $rule['firing_strength'] * $weight;
            $center = $rule['output_center'];

            $numerator += ($effectiveAlpha * $center);
            $denominator += $effectiveAlpha;

            $steps[] = [
                'rule_code' => $rule['code'],
                'firing_strength' => $rule['firing_strength'],
                'weight' => $weight,
                'output_center' => $center,
                'product' => round($effectiveAlpha * $center, 4),
            ];
        }

        $crispValue = $denominator > 0 ? round($numerator / $denominator, 4) : 0.0;

        return [
            'method' => 'weighted-average',
            'crisp_value' => $crispValue,
            'numerator' => round($numerator, 4),
            'denominator' => round($denominator, 4),
            'steps' => $steps,
        ];
    }

    /**
     * Winner-Take-All Method (WTA):
     * Memilih aturan aktif dengan derajat penembakan (firing strength) tertinggi
     */
    public function winnerTakeAll(array $evaluatedRules): array
    {
        $maxAlpha = -1.0;
        $winningRule = null;

        foreach ($evaluatedRules as $rule) {
            $effectiveAlpha = $rule['firing_strength'] * ($rule['weight'] ?? 1.0);
            if ($effectiveAlpha > $maxAlpha) {
                $maxAlpha = $effectiveAlpha;
                $winningRule = $rule;
            }
        }

        $crispValue = $winningRule ? (float) $winningRule['output_center'] : 0.0;

        return [
            'method' => 'winner-take-all',
            'crisp_value' => $crispValue,
            'winning_rule' => $winningRule ? $winningRule['code'] : null,
            'max_alpha' => $maxAlpha,
        ];
    }

    /**
     * Centroid (Center of Gravity approximation).
     */
    public function centroid(array $inferenceData): array
    {
        $strengths = $inferenceData['output_set_strengths'] ?? [];
        $numerator = 0.0;
        $denominator = 0.0;

        // Discretized sampling across 0 - 100 with step = 5
        for ($y = 0; $y <= 100; $y += 5) {
            $muY = 0.0;
            // Evaluasi himpunan output
            if ($y <= 60 && isset($strengths['TIDAK DITERIMA'])) {
                $muY = max($muY, min($strengths['TIDAK DITERIMA'], (60 - $y) / 60));
            }
            if ($y >= 65 && isset($strengths['DITERIMA'])) {
                $muY = max($muY, min($strengths['DITERIMA'], ($y - 65) / 25));
            }

            $numerator += ($y * $muY);
            $denominator += $muY;
        }

        $crispValue = $denominator > 0 ? round($numerator / $denominator, 4) : 0.0;

        return [
            'method' => 'centroid',
            'crisp_value' => $crispValue,
            'numerator' => round($numerator, 4),
            'denominator' => round($denominator, 4),
        ];
    }
}
