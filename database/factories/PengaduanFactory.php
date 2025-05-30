<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PengaduanFactory extends Factory
{
    public function definition(): array
    {
        static $urut = 1;
        $tanggal = date('dmy'); // contoh: 310525 (31 Mei 2025)
        $id = 'PD' . $tanggal . str_pad($urut++, 4, '0', STR_PAD_LEFT); // 0001, 0002, dst

        return [
            'id'           => $id,
            'npsn'         => '10000001', // nanti bisa di-overwrite di seeder
            'judul'        => $this->faker->sentence,
            'deskripsi'    => $this->faker->paragraph,
            'kategori'     => $this->faker->randomElement(['Kendala Teknis','Pelayanan', 'Lainnya']),
            'bukti_path'   => null,
            'status'       => $this->faker->randomElement(['Menunggu', 'Diproses', 'Selesai']),
            'submitted_by' => null,
            'verified_by'  => null,
            'verified_at'  => null,
            'catatan'      => $this->faker->sentence,
            'created_at'   => $this->faker->dateTimeBetween('-5 years', 'now'),
            'updated_at'   => now(),
        ];
    }
}