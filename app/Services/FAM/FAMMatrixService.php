<?php

namespace App\Services\FAM;

use App\Models\FamRule;

class FAMMatrixService
{
    /**
     * Evaluate all active FAM rules against fuzzification results.
     *
     * @param array<string, array<string, mixed>> $fuzzificationData
     * @param string $encodingMethod 'correlation-minimum' | 'correlation-product'
     * @return array<int, array<string, mixed>>
     */
    public function evaluateRules(array $fuzzificationData, string $encodingMethod = 'correlation-minimum'): array
    {
        $rules = FamRule::with([
            'academicMembership',
            'btqMembership',
            'tahfidzMembership',
            'interviewMembership',
            'outputMembership',
        ])
        ->where('is_active', true)
        ->get();

        $evaluatedRules = [];

        foreach ($rules as $rule) {
            $muAkademik = $fuzzificationData['AKADEMIK']['degrees'][$rule->academicMembership->name] ?? 0.0;
            $muBtq = $fuzzificationData['BTQ']['degrees'][$rule->btqMembership->name] ?? 0.0;
            $muTahfidz = $fuzzificationData['TAHFIDZ']['degrees'][$rule->tahfidzMembership->name] ?? 0.0;
            $muWawancara = $fuzzificationData['WAWANCARA']['degrees'][$rule->interviewMembership->name] ?? 0.0;

            // Firing strength α_k menggunakan t-norm Minimum
            $alpha = min($muAkademik, $muBtq, $muTahfidz, $muWawancara);

            // Representasi center / centroid nilai output
            $outputCenter = $this->getMembershipCenter($rule->outputMembership);

            $evaluatedRules[] = [
                'rule_id' => $rule->id,
                'code' => $rule->code,
                'premise' => [
                    'akademik' => ['set' => $rule->academicMembership->name, 'degree' => $muAkademik],
                    'btq' => ['set' => $rule->btqMembership->name, 'degree' => $muBtq],
                    'tahfidz' => ['set' => $rule->tahfidzMembership->name, 'degree' => $muTahfidz],
                    'wawancara' => ['set' => $rule->interviewMembership->name, 'degree' => $muWawancara],
                ],
                'firing_strength' => $alpha,
                'weight' => (float) $rule->weight,
                'output_set' => $rule->outputMembership->name,
                'output_center' => $outputCenter,
                'encoding_method' => $encodingMethod,
                'is_active' => $alpha > 0,
            ];
        }

        return $evaluatedRules;
    }

    /**
     * Get representative central value for output membership set.
     */
    public function getMembershipCenter($membership): float
    {
        $a = (float) $membership->param_a;
        $b = (float) $membership->param_b;
        $c = $membership->param_c !== null ? (float) $membership->param_c : null;

        // Jika ada nilai c (segitiga), center = b
        if ($c !== null) {
            return $b;
        }

        // Trapesium kiri: center representasi di sekitar (a + b) / 2 atau threshold
        if ($membership->curve_type === 'shoulder_left') {
            return round(($a + $b) / 2, 2);
        }

        // Trapesium kanan: center representasi di sekitar (a + b) / 2 atau b
        if ($membership->curve_type === 'shoulder_right') {
            return round(($a + $b) / 2, 2);
        }

        return round(($a + $b) / 2, 2);
    }
}
