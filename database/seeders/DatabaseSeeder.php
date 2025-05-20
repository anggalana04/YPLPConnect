<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed some sekolah
        $sekolahs = collect([
            ['npsn' => '10000001', 'nama' => 'SDN 1 Contoh', 'jenjang' => 'SD', 'alamat' => 'Jl. Mawar 1', 'email' => 'sdn1@example.com'],
            ['npsn' => '10000002', 'nama' => 'SMPN 1 Contoh', 'jenjang' => 'SMP', 'alamat' => 'Jl. Melati 2', 'email' => 'smpn1@example.com'],
        ]);
        foreach ($sekolahs as $data) {
            Sekolah::firstOrCreate(['npsn' => $data['npsn']], $data);
        }

        // Get all npsn for sekolah
        $npsnList = Sekolah::pluck('npsn')->toArray();

        // Create users
        User::factory(10)->make()->each(function ($user) use ($npsnList) {
            if ($user->role === 'operator_sekolah') {
                $user->npsn = $npsnList[array_rand($npsnList)];
            } else {
                $user->npsn = null;
            }
            $user->save();
        });

        // Seed siswa for each sekolah
        foreach ($npsnList as $npsn) {
            \App\Models\Siswa::factory(20)->create([
                'npsn' => $npsn,
            ]);
        }
    }
}
