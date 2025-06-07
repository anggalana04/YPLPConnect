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

    // =======================
    // BUAT DATA SEKOLAH
    // =======================
    for ($i = 1; $i <= 20; $i++) {
        Sekolah::create([
            'npsn'    => '100000' . str_pad($i, 2, '0', STR_PAD_LEFT),
            'nama'    => 'Sekolah ' . $faker->company,
            'jenjang' => $faker->randomElement(['SD', 'SMP', 'SMA', 'SMK']),
            'alamat'  => $faker->address,
            'email'   => $faker->unique()->safeEmail,
        ]);
    }

    // Ambil semua NPSN dari Sekolah
    $npsnList = Sekolah::pluck('npsn')->toArray();

    // =======================
    // BUAT DATA USER
    // =======================
    User::factory(10)->make()->each(function ($user) use ($npsnList) {
        if ($user->role === 'operator_sekolah') {
            $user->npsn = $npsnList[array_rand($npsnList)];
        }
        $user->save();
    });

    // Ambil NPSN dari user yang role-nya operator_sekolah
    $npsnFromUsers = User::where('role', 'operator_sekolah')->pluck('npsn')->filter()->unique()->values()->all();

    // =======================
    // BUAT DATA GURU
    // =======================
    Guru::factory(100)->make()->each(function ($guru) use ($npsnFromUsers) {
        $tahunSekarang = date('Y');
        $tahun = $tahunSekarang - rand(0, 5);

        $guru->npsn = $npsnFromUsers[array_rand($npsnFromUsers)];
        $guru->status = 'aktif'; // Tambahkan ini
        $guru->created_at = $tahun . '-01-01';
        $guru->updated_at = now();
        $guru->save();
    });

    // =======================
    // BUAT SISWA, PENGADUAN, KEUANGAN PER SEKOLAH
    // =======================
    $bulanList = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $tahunSekarang = date('Y');

    foreach ($npsnFromUsers as $npsn) {
        // Siswa
        Siswa::factory(50)->create([
            'npsn' => $npsn,
            'status' => 'aktif', // Tambahkan ini
        ]);

        // Pengaduan
        Pengaduan::factory(15)->create([
            'npsn' => $npsn,
            'status' => 'Menunggu', // Ubah agar semua status awalnya 'Menunggu'
        ]);


        // Keuangan
        $jumlahSiswa = Siswa::where('npsn', $npsn)->count();

        for ($tahun = $tahunSekarang - 2; $tahun <= $tahunSekarang; $tahun++) {
            $bulanLoop = $bulanList;

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
                    'status'     => 'Menunggu', // Tambahkan ini
                ]);
            }
        }
    }

    // =======================
    // BUAT DOKUMEN PER SEKOLAH
    // =======================
    foreach ($npsnFromUsers as $npsn) {
        for ($i = 1; $i <= 15; $i++) {
            Dokumen::create([
                'nuptk'             => Guru::where('npsn', $npsn)->inRandomOrder()->value('nuptk'),
                'nama'              => $faker->name,
                'tempat_lahir'      => $faker->city,
                'tanggal_lahir'     => $faker->date('Y-m-d', '-25 years'),
                'alamat_unit_kerja' => $faker->address,
                'jenis_sk'          => $faker->randomElement(['SK Pengangkatan', 'SK Pensiun', 'SK Mutasi']),
                 'status'            => 'Menunggu', // Ubah ini agar default-nya 'Menunggu'
                'file_path'         => 'dokumen/' . Str::random(10) . '.pdf',
                'submitted_by'      => 1,
                'verified_by'       => null,
                'verified_at'       => null,
                'catatan'           => $faker->sentence,
            ]);
        }
    }
}
}
