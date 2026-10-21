<?php

namespace App\Http\Requests;

use App\Support\Quran;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiswaRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50'],
            'jurusan' => ['nullable', Rule::in(Quran::majors())],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
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
            'nama' => 'Nama siswa',
            'kelas' => 'Kelas',
            'jurusan' => 'Jurusan',
            'tahun_ajaran' => 'Tahun ajaran',
        ];
    }

    /**
     * Custom messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute melebihi batas panjang karakter (:max).',
            'in' => ':attribute tidak valid.',
        ];
    }
}
