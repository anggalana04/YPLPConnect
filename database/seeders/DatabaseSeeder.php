<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Pengaduan;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Buat 20 data sekolah random
        for ($i = 1; $i <= 20; $i++) {
            Sekolah::create([
                'npsn'    => '100000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'nama'    => 'Sekolah ' . $faker->company,
                'jenjang' => $faker->randomElement(['SD', 'SMP', 'SMA', 'SMK']),
                'alamat'  => $faker->address,
                'email'   => $faker->unique()->safeEmail,
            ]);
        }

        // Ambil semua NPSN yang sudah dibuat
        $npsnList = Sekolah::pluck('npsn')->toArray();

        // Buat 10 user
        User::factory(10)->make()->each(function ($user) use ($npsnList) {
            $user->npsn = $user->role === 'operator_sekolah'
                ? $npsnList[array_rand($npsnList)]
                : null;
            $user->save();
        });

        // Buat 3000 guru dengan tahun acak 2019-2024
        Guru::factory(100)->make()->each(function ($guru) use ($npsnList) {
            $tahunSekarang = date('Y');
            $tahun = $tahunSekarang - rand(0, 5);

            $guru->npsn = $npsnList[array_rand($npsnList)];
            $guru->created_at = $tahun . '-01-01';
            $guru->updated_at = now();
            $guru->save();
        });

        // Buat 50 siswa per sekolah
        foreach ($npsnList as $npsn) {
            Siswa::factory(50)->create([
                'npsn' => $npsn,
            ]);

            // Buat 20 pengaduan per sekolah
            Pengaduan::factory(15)->create([
                'npsn' => $npsn,
            ]);
        }
    }
}
