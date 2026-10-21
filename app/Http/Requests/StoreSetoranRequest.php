<?php

namespace App\Http\Requests;

use App\Support\Quran;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSetoranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'waktu_setoran' => ['required', 'date'],
            'petugas_id' => ['required', 'exists:petugas,id'],
            'siswa_id' => ['required', 'exists:siswas,id'],
            'kategori' => ['required', Rule::in(Quran::categories())],
            'surah' => ['required', 'string', 'max:255'],
            'ayat' => ['required', 'string', 'max:100'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'waktu_setoran' => 'Waktu setoran',
            'petugas_id' => 'Nama petugas',
            'siswa_id' => 'Nama siswa',
            'kategori' => 'Kategori setoran',
            'surah' => 'Surah',
            'ayat' => 'Rentang ayat',
            'nilai' => 'Nilai setoran',
            'catatan' => 'Catatan',
        ];
    }

    /**
     * Custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak ditemukan dalam sistem.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal bernilai :min.',
            'max' => ':attribute maksimal bernilai :max.',
        ];
    }
}
