<?php

namespace Database\Factories;

use App\Models\Departemen;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dosen>
 */
class DosenFactory extends Factory
{
    protected $model = Dosen::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => fake()->unique()->numerify('19################'),
            'nama' => 'Dr. ' . fake()->name(),
            'email' => fake()->unique()->userName() . '@its.ac.id',
            'departemen_id' => Departemen::factory(),
        ];
    }
}
