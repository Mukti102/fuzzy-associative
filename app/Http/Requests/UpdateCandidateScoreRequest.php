<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateScoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'academic_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'btq_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tahfidz_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'interview_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'academic_score' => 'Nilai Akademik / Rapor',
            'btq_score' => 'Nilai Tes Baca Tulis Al-Qur\'an (BTQ)',
            'tahfidz_score' => 'Nilai Tes Hafalan Al-Qur\'an (Tahfidz)',
            'interview_score' => 'Nilai Tes Wawancara',
            'notes' => 'Catatan Tambahan',
        ];
    }

    public function messages(): array
    {
        return [
            'academic_score.min' => 'Nilai Akademik minimal adalah 0.',
            'academic_score.max' => 'Nilai Akademik maksimal adalah 100.',
            'btq_score.min' => 'Nilai BTQ minimal adalah 0.',
            'btq_score.max' => 'Nilai BTQ maksimal adalah 100.',
            'tahfidz_score.min' => 'Nilai Tahfidz minimal adalah 0.',
            'tahfidz_score.max' => 'Nilai Tahfidz maksimal adalah 100.',
            'interview_score.min' => 'Nilai Wawancara minimal adalah 0.',
            'interview_score.max' => 'Nilai Wawancara maksimal adalah 100.',
        ];
    }
}
