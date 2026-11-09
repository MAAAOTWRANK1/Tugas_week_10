<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('tambah-mahasiswa') ?? false;
    }

    /**
     * Aturan validasi untuk menyimpan data mahasiswa baru.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nrp' => ['required', 'digits:10', 'unique:mahasiswas,nrp'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:mahasiswas,email'],
            'departemen_id' => ['required', 'exists:departemens,id'],
            'dosen_id' => ['nullable', 'exists:dosens,id'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * Pesan error validasi berbahasa Indonesia yang jelas.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nrp.required' => 'NRP wajib diisi. Masukkan 10 digit angka NRP.',
            'nrp.digits' => 'NRP harus 10 digit angka.',
            'nrp.unique' => 'NRP ini sudah terdaftar. Gunakan NRP lain.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid. Contoh: nama@mhs.its.ac.id.',
            'email.unique' => 'Email ini sudah terdaftar pada mahasiswa lain.',
            'departemen_id.required' => 'Pilih salah satu departemen.',
            'departemen_id.exists' => 'Departemen yang dipilih tidak ditemukan.',
        ];
    }
}
