<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSelectionPeriodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
            'passing_grade' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Periode Seleksi',
            'academic_year' => 'Tahun Ajaran',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Selesai',
            'is_active' => 'Status Periode Aktif',
            'passing_grade' => 'Batas Kelulusan (Passing Grade)',
            'quota' => 'Kuota Penerimaan',
            'description' => 'Keterangan / Deskripsi',
        ];
    }

    public function messages(): array
    {
        return [
            'academic_year.regex' => 'Format tahun ajaran harus berupa format tahun (contoh: 2026/2027).',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }
}
