<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Dokumen;
use App\Models\Sekolah;
use App\Models\Keuangan;
use App\Models\Pengaduan;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

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

        // Buat 100 guru
        Guru::factory(100)->make()->each(function ($guru) use ($npsnList) {
            $tahunSekarang = date('Y');
            $tahun = $tahunSekarang - rand(0, 5);

            $guru->npsn = $npsnList[array_rand($npsnList)];
            $guru->created_at = $tahun . '-01-01';
            $guru->updated_at = now();
            $guru->save();
        });

        // Daftar bulan
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $tahunSekarang = date('Y');

        // Buat siswa, pengaduan, dan keuangan per sekolah
        foreach ($npsnList as $npsn) {
            // 50 siswa per sekolah
            Siswa::factory(50)->create(['npsn' => $npsn]);

            // 15 pengaduan per sekolah
            Pengaduan::factory(15)->create(['npsn' => $npsn]);

            $jumlahSiswa = Siswa::where('npsn', $npsn)->count();

            // Data keuangan untuk 3 tahun terakhir
            for ($tahun = $tahunSekarang - 2; $tahun <= $tahunSekarang; $tahun++) {
                $bulanLoop = $bulanList;

                // Jika tahun adalah tahun sekarang, potong bulan sampai bulan saat ini saja
                if ($tahun === (int) $tahunSekarang) {
                    $bulanSekarang = Carbon::now()->month;
                    $bulanLoop = array_slice($bulanList, 0, $bulanSekarang);
                }

                foreach ($bulanLoop as $bulan) {
                    Keuangan::factory()->create([
                        'npsn'       => $npsn,
                        'tahun'      => $tahun,
                        'bulan'      => $bulan,
                        'jumlah_spp' => 2000 * $jumlahSiswa,
                    ]);
                }
            }
        }

        // Buat 15 dokumen per sekolah
foreach ($npsnList as $npsn) {
    for ($i = 1; $i <= 15; $i++) {
        $dokumen = new Dokumen([
            'nuptk'             => Guru::inRandomOrder()->value('nuptk'), // ambil NUPTK valid dari tabel guru
            'nama'              => $faker->name,
            'tempat_lahir'      => $faker->city,
            'tanggal_lahir'     => $faker->date('Y-m-d', '-25 years'),
            'alamat_unit_kerja' => $faker->address,
            'jenis_sk'          => $faker->randomElement(['SK Pengangkatan', 'SK Pensiun', 'SK Mutasi']),
            'status'            => $faker->randomElement(['Menunggu', 'Disetujui', 'Ditolak']),
            'file_path'         => 'dokumen/' . Str::random(10) . '.pdf',
            'submitted_by'      => 1,
            'verified_by'       => null,
            'verified_at'       => null,
            'catatan'           => $faker->sentence,
        ]);

        $dokumen->save();
    }
}

    }
}
