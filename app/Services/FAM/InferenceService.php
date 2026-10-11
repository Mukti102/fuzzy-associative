<?php

namespace App\Services\FAM;

class InferenceService
{
    /**
     * Aggregate output sets based on composition method.
     *
     * @param array<int, array<string, mixed>> $evaluatedRules
     * @param string $compositionMethod 'max-min' | 'max-product'
     * @return array<string, mixed>
     */
    public function aggregate(array $evaluatedRules, string $compositionMethod = 'max-min'): array
    {
        $activeRules = array_filter($evaluatedRules, fn ($r) => $r['firing_strength'] > 0);

        // Agregasi per himpunan output (contoh: 'DITERIMA', 'TIDAK DITERIMA')
        $outputSetStrengths = [];

        foreach ($activeRules as $rule) {
            $set = $rule['output_set'];
            $alpha = $rule['firing_strength'] * $rule['weight'];

            if (!isset($outputSetStrengths[$set])) {
                $outputSetStrengths[$set] = $alpha;
            } else {
                // T-conorm Max aggregation
                $outputSetStrengths[$set] = max($outputSetStrengths[$set], $alpha);
            }
        }

        return [
            'composition_method' => $compositionMethod,
            'total_active_rules' => count($activeRules),
            'output_set_strengths' => $outputSetStrengths,
            'rules_summary' => $activeRules,
        ];
    }
}
