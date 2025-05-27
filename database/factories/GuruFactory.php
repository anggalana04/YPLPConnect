<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GuruFactory extends Factory
{
    protected $model = \App\Models\Guru::class;

    public function definition(): array
    {
        return [
            'nuptk'         => $this->faker->unique()->numerify(str_repeat('#', 16)),
            'npsn'          => $this->faker->numerify(str_repeat('#', 8)), // akan di-overwrite di seeder
            'npa'           => $this->faker->boolean(70) ? $this->faker->unique()->numerify(str_repeat('#', 16)) : null, // 70% isi, 30% null
            'nama'          => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir'  => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-20 years'),
            'alamat'        => $this->faker->address(),
            'no_hp'         => $this->faker->optional()->regexify('08[0-9]{9,12}'), // 11-14 digit
            'status'        => $this->faker->randomElement(['Aktif', 'Nonaktif']),
        ];
    }
}
