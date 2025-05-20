<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    protected $model = \App\Models\Siswa::class;

    public function definition(): array
    {
        return [
            'nisn' => $this->faker->unique()->numerify('##########'),
            'npsn' => null, // will be set in seeder
            'nama' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-6 years'),
            'alamat' => $this->faker->address(),
            'status' => $this->faker->randomElement(['Aktif', 'Nonaktif']),
        ];
    }
}
