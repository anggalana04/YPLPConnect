<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PengaduanFactory extends Factory
{
    public function definition(): array
    {
        static $urut = 1;
        $tanggal = date('dmy');
        $id = 'PD' . $tanggal . str_pad($urut++, 4, '0', STR_PAD_LEFT);

        return [
            'id'           => $id,
            'npsn'         => '10000001',
            'judul'        => $this->faker->sentence,       // ✅ otomatis pakai id_ID
            'deskripsi'    => $this->faker->paragraph,      // ✅ otomatis pakai id_ID
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
