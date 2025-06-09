<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Keuangan;
use Illuminate\Support\Str;

class GenerateKeuanganPreserved extends Command
{
    protected $signature = 'keuangan:preserve {tahun?}';
    protected $description = 'Generate preserved keuangan data for each sekolah and each month in a year';

    public function handle()
    {
        $tahun = $this->argument('tahun') ?? date('Y');
        $bulanList = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        $sekolahs = Sekolah::all();
        $created = 0;
        foreach ($sekolahs as $sekolah) {
            $jumlahSiswa = Siswa::where('npsn', $sekolah->npsn)->count();
            foreach ($bulanList as $bulan) {
                $exists = Keuangan::where('npsn', $sekolah->npsn)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->exists();
                if (!$exists) {
                    Keuangan::create([
                        'id' => 'TR' . $tahun . $sekolah->npsn . $bulan,
                        'npsn' => $sekolah->npsn,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'jumlah_spp' => 2000 * $jumlahSiswa,
                        'status' => 'Menunggu',
                        'bukti_path' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                        'catatan' => null,
                    ]);
                    $created++;
                }
            }
        }
        $this->info("Generated $created preserved keuangan records for tahun $tahun.");
    }
}
