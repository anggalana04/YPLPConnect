<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeuanganFactory extends Factory
{

    public function definition(): array
    {
        static $urut = 1;
        $tanggal = now()->format('dmy');
        $id = 'TR' . now()->format('dmy') . str_pad($urut++, 4, '0', STR_PAD_LEFT);

        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        return [
            'id'          =>$id,
            'npsn'        => '10000001', // bisa di-overwrite di seeder
            'tahun'       => $this->faker->numberBetween(date('Y') - 2, date('Y')),
            'bulan'       => $this->faker->randomElement($bulanList),
            'jumlah_spp'  => 2000,
            'status'      => $this->faker->randomElement(['Menunggu','Disetujui', 'Ditolak']),
            'bukti_path'  => null,
            'verified_by' => null,
            'verified_at' => null,
            'catatan'     => $this->faker->optional()->sentence,
        ];
    }
}