<?php

namespace Database\Seeders;

use App\Models\FamRule;
use App\Models\FuzzyMembership;
use App\Models\FuzzyVariable;
use Illuminate\Database\Seeder;

class FuzzyParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Variabel Kriteria Input
        $inputVariables = [
            [
                'code' => 'AKADEMIK',
                'name' => 'Nilai Akademik / Rapor',
                'type' => 'input',
                'min_range' => 0,
                'max_range' => 100,
                'unit' => 'Skor (0-100)',
                'memberships' => [
                    ['name' => 'Rendah', 'curve_type' => 'shoulder_left', 'param_a' => 0, 'param_b' => 60, 'param_c' => null, 'param_d' => null],
                    ['name' => 'Sedang', 'curve_type' => 'triangular', 'param_a' => 50, 'param_b' => 70, 'param_c' => 85, 'param_d' => null],
                    ['name' => 'Tinggi', 'curve_type' => 'shoulder_right', 'param_a' => 75, 'param_b' => 90, 'param_c' => null, 'param_d' => null],
                ],
            ],
            [
                'code' => 'BTQ',
                'name' => 'Tes Baca Tulis Al-Qur\'an',
                'type' => 'input',
                'min_range' => 0,
                'max_range' => 100,
                'unit' => 'Skor (0-100)',
                'memberships' => [
                    ['name' => 'Rendah', 'curve_type' => 'shoulder_left', 'param_a' => 0, 'param_b' => 60, 'param_c' => null, 'param_d' => null],
                    ['name' => 'Sedang', 'curve_type' => 'triangular', 'param_a' => 50, 'param_b' => 70, 'param_c' => 85, 'param_d' => null],
                    ['name' => 'Tinggi', 'curve_type' => 'shoulder_right', 'param_a' => 75, 'param_b' => 90, 'param_c' => null, 'param_d' => null],
                ],
            ],
            [
                'code' => 'TAHFIDZ',
                'name' => 'Tes Hafalan Al-Qur\'an (Tahfidz)',
                'type' => 'input',
                'min_range' => 0,
                'max_range' => 100,
                'unit' => 'Skor (0-100)',
                'memberships' => [
                    ['name' => 'Rendah', 'curve_type' => 'shoulder_left', 'param_a' => 0, 'param_b' => 60, 'param_c' => null, 'param_d' => null],
                    ['name' => 'Sedang', 'curve_type' => 'triangular', 'param_a' => 50, 'param_b' => 70, 'param_c' => 85, 'param_d' => null],
                    ['name' => 'Tinggi', 'curve_type' => 'shoulder_right', 'param_a' => 75, 'param_b' => 90, 'param_c' => null, 'param_d' => null],
                ],
            ],
            [
                'code' => 'WAWANCARA',
                'name' => 'Tes Wawancara / Kepribadian',
                'type' => 'input',
                'min_range' => 0,
                'max_range' => 100,
                'unit' => 'Skor (0-100)',
                'memberships' => [
                    ['name' => 'Rendah', 'curve_type' => 'shoulder_left', 'param_a' => 0, 'param_b' => 60, 'param_c' => null, 'param_d' => null],
                    ['name' => 'Sedang', 'curve_type' => 'triangular', 'param_a' => 50, 'param_b' => 70, 'param_c' => 85, 'param_d' => null],
                    ['name' => 'Tinggi', 'curve_type' => 'shoulder_right', 'param_a' => 75, 'param_b' => 90, 'param_c' => null, 'param_d' => null],
                ],
            ],
        ];

        // 2. Variabel Output Kelayakan
        $outputVariable = [
            'code' => 'OUTPUT',
            'name' => 'Kelayakan Penerimaan Santri',
            'type' => 'output',
            'min_range' => 0,
            'max_range' => 100,
            'unit' => 'Nilai Akhir (0-100)',
            'memberships' => [
                ['name' => 'TIDAK DITERIMA', 'curve_type' => 'shoulder_left', 'param_a' => 0, 'param_b' => 60, 'param_c' => null, 'param_d' => null],
                ['name' => 'DITERIMA', 'curve_type' => 'shoulder_right', 'param_a' => 65, 'param_b' => 90, 'param_c' => null, 'param_d' => null],
            ],
        ];

        $createdMemberships = [];

        foreach (array_merge($inputVariables, [$outputVariable]) as $varData) {
            $membershipsData = $varData['memberships'];
            unset($varData['memberships']);

            $variable = FuzzyVariable::firstOrCreate(['code' => $varData['code']], $varData);

            foreach ($membershipsData as $m) {
                $membership = FuzzyMembership::firstOrCreate(
                    [
                        'fuzzy_variable_id' => $variable->id,
                        'name' => $m['name'],
                    ],
                    $m
                );
                $createdMemberships[$varData['code']][$m['name']] = $membership->id;
            }
        }

        // 3. Basis Aturan Asosiasi Awal (Default Rule Base)
        // Hubungan asosiasi logika FAM dasar:
        // Jika kriteria bernilai dominan tinggi/sedang -> Diterima, jika dominan rendah -> Tidak Diterima
        $rules = [
            // R1: Semua Tinggi -> DITERIMA
            ['code' => 'R1', 'akademik' => 'Tinggi', 'btq' => 'Tinggi', 'tahfidz' => 'Tinggi', 'wawancara' => 'Tinggi', 'output' => 'DITERIMA'],
            // R2: Semua Sedang -> DITERIMA
            ['code' => 'R2', 'akademik' => 'Sedang', 'btq' => 'Sedang', 'tahfidz' => 'Sedang', 'wawancara' => 'Sedang', 'output' => 'DITERIMA'],
            // R3: Semua Rendah -> TIDAK DITERIMA
            ['code' => 'R3', 'akademik' => 'Rendah', 'btq' => 'Rendah', 'tahfidz' => 'Rendah', 'wawancara' => 'Rendah', 'output' => 'TIDAK DITERIMA'],
            // R4: Tahfidz & BTQ Tinggi, Akademik & Wawancara Sedang -> DITERIMA
            ['code' => 'R4', 'akademik' => 'Sedang', 'btq' => 'Tinggi', 'tahfidz' => 'Tinggi', 'wawancara' => 'Sedang', 'output' => 'DITERIMA'],
            // R5: Akademik & Wawancara Tinggi, Tahfidz & BTQ Sedang -> DITERIMA
            ['code' => 'R5', 'akademik' => 'Tinggi', 'btq' => 'Sedang', 'tahfidz' => 'Sedang', 'wawancara' => 'Tinggi', 'output' => 'DITERIMA'],
            // R6: BTQ & Tahfidz Rendah -> TIDAK DITERIMA
            ['code' => 'R6', 'akademik' => 'Sedang', 'btq' => 'Rendah', 'tahfidz' => 'Rendah', 'wawancara' => 'Sedang', 'output' => 'TIDAK DITERIMA'],
            // R7: Akademik Tinggi, BTQ Tinggi, Tahfidz Sedang, Wawancara Rendah -> DITERIMA
            ['code' => 'R7', 'akademik' => 'Tinggi', 'btq' => 'Tinggi', 'tahfidz' => 'Sedang', 'wawancara' => 'Rendah', 'output' => 'DITERIMA'],
            // R8: Akademik Rendah, BTQ Sedang, Tahfidz Rendah, Wawancara Rendah -> TIDAK DITERIMA
            ['code' => 'R8', 'akademik' => 'Rendah', 'btq' => 'Sedang', 'tahfidz' => 'Rendah', 'wawancara' => 'Rendah', 'output' => 'TIDAK DITERIMA'],
            // R9: Tahfidz Tinggi, BTQ Tinggi, Akademik Rendah, Wawancara Sedang -> DITERIMA
            ['code' => 'R9', 'akademik' => 'Rendah', 'btq' => 'Tinggi', 'tahfidz' => 'Tinggi', 'wawancara' => 'Sedang', 'output' => 'DITERIMA'],
            // R10: 3 Kriteria Rendah -> TIDAK DITERIMA
            ['code' => 'R10', 'akademik' => 'Rendah', 'btq' => 'Rendah', 'tahfidz' => 'Sedang', 'wawancara' => 'Rendah', 'output' => 'TIDAK DITERIMA'],
        ];

        foreach ($rules as $r) {
            FamRule::firstOrCreate(
                ['code' => $r['code']],
                [
                    'academic_membership_id' => $createdMemberships['AKADEMIK'][$r['akademik']],
                    'btq_membership_id' => $createdMemberships['BTQ'][$r['btq']],
                    'tahfidz_membership_id' => $createdMemberships['TAHFIDZ'][$r['tahfidz']],
                    'interview_membership_id' => $createdMemberships['WAWANCARA'][$r['wawancara']],
                    'output_membership_id' => $createdMemberships['OUTPUT'][$r['output']],
                    'weight' => 1.00,
                    'is_active' => true,
                ]
            );
        }
    }
}
