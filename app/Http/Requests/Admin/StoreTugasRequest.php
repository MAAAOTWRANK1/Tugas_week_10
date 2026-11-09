<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTugasRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('tambah-tugas') ?? false;
    }

    /**
     * Aturan validasi penyimpanan tugas.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'mata_kuliah' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tenggat' => ['required', 'date'],
            'dosen_id' => ['nullable', 'exists:dosens,id'],
            'mahasiswa_id' => ['nullable', 'exists:mahasiswas,id'],
            'nilai' => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }

    /**
     * Pesan error berbahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul tugas wajib diisi.',
            'mata_kuliah.required' => 'Nama mata kuliah wajib diisi.',
            'tenggat.required' => 'Tenggat pengumpulan wajib diisi.',
            'tenggat.date' => 'Format tanggal tenggat tidak valid.',
            'nilai.integer' => 'Nilai harus berupa angka bulat antara 0 hingga 100.',
            'nilai.min' => 'Nilai minimal adalah 0.',
            'nilai.max' => 'Nilai maksimal adalah 100.',
        ];
    }
}
