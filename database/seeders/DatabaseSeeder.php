<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;

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
            $user->npsn = $user->role === 'operator_sekolah' ? $npsnList[array_rand($npsnList)] : null;
            $user->save();
        });

        // Buat 20 guru
        Guru::factory(20)->make()->each(function ($guru) use ($npsnList) {
            $guru->npsn = $npsnList[array_rand($npsnList)];
            $guru->save();
        });

        // Buat 50 siswa per sekolah
        foreach ($npsnList as $npsn) {
            Siswa::factory(50)->create([
                'npsn' => $npsn,
            ]);
        }
    }
}
