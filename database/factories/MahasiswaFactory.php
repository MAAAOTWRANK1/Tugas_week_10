<?php

namespace Database\Factories;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nrp' => fake()->unique()->numerify('50252#####'),
            'nama' => fake()->name(),
            'email' => fake()->unique()->userName().'@mhs.its.ac.id',
            'departemen_id' => Departemen::factory(),
            'dosen_id' => Dosen::factory(),
            'is_active' => fake()->boolean(85),
        ];
    }
}
