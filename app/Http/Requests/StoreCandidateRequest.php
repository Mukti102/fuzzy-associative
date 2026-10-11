<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCandidateRequest extends FormRequest
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
            'selection_period_id' => ['required', 'exists:selection_periods,id'],
            'registration_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('candidates', 'registration_number')
                    ->where(fn ($query) => $query->where('selection_period_id', $this->selection_period_id)),
            ],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'school_origin' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function attributes(): array
    {
        return [
            'selection_period_id' => 'Periode Seleksi',
            'registration_number' => 'Nomor Pendaftaran / Peserta',
            'name' => 'Nama Lengkap Calon Santri',
            'gender' => 'Jenis Kelamin',
            'birth_place' => 'Tempat Lahir',
            'birth_date' => 'Tanggal Lahir',
            'school_origin' => 'Asal Sekolah',
            'address' => 'Alamat Lengkap',
            'phone' => 'Nomor Telepon / WhatsApp',
        ];
    }

    public function messages(): array
    {
        return [
            'registration_number.unique' => 'Nomor pendaftaran ini sudah terdaftar pada periode seleksi yang dipilih.',
            'gender.in' => 'Pilihan jenis kelamin harus Laki-laki (L) atau Perempuan (P).',
        ];
    }
}
