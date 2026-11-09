<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tugas>
 */
class TugasFactory extends Factory
{
    protected $model = Tugas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mataKuliahList = [
            'Pemrograman Berbasis Kerangka Kerja',
            'Rekayasa Perangkat Lunak',
            'Sistem Basis Data',
            'Jaringan Komputer',
            'Kecerdasan Artifisial',
            'Keamanan Informasi',
        ];

        $judulPrefix = [
            'Implementasi Modul',
            'Analisis Studi Kasus',
            'Laporan Praktikum',
            'Perancangan Arsitektur',
            'Evaluasi Performa',
        ];

        return [
            'judul' => fake()->randomElement($judulPrefix) . ' ' . fake()->words(2, true),
            'deskripsi' => fake()->sentence(12),
            'mata_kuliah' => fake()->randomElement($mataKuliahList),
            'tenggat' => fake()->dateTimeBetween('+1 days', '+30 days'),
            'dosen_id' => Dosen::factory(),
            'mahasiswa_id' => Mahasiswa::factory(),
            'nilai' => fake()->optional(0.6)->numberBetween(70, 100),
        ];
    }
}
