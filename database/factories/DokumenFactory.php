<?php

namespace Database\Factories;

use App\Models\Guru;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DokumenFactory extends Factory
{
    protected $model = \App\Models\Dokumen::class;

    public function definition()
    {
        return [
            'nuptk'             => Guru::inRandomOrder()->value('nuptk'), // Ambil NUPTK dari guru yang sudah ada
            'nama'              => $this->faker->name,
            'tempat_lahir'      => $this->faker->city,
            'tanggal_lahir'     => $this->faker->date('Y-m-d', '-25 years'),
            'alamat_unit_kerja' => $this->faker->address,
            'jenis_sk'          => $this->faker->randomElement(['SK Pengangkatan', 'SK Pensiun', 'SK Mutasi']),
            'status'            => $this->faker->randomElement(['Menunggu', 'Diterima', 'Ditolak', 'Selesai', 'Ditolak', 'Diproses']),
            'file_path'         => 'dokumen/' . Str::random(10) . '.pdf',
            'submitted_by'      => 1, // Sesuaikan jika ingin ambil dari user tertentu
            'verified_by'       => null,
            'verified_at'       => null,
            'catatan'           => $this->faker->sentence,
        ];
    }
}
