<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // jika kamu pakai heading row di Excel

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
     * Map setiap baris Excel ke model Siswa
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Jika pakai heading row, pastikan key sesuai nama kolom heading Excel, contoh:
        // 'nisn', 'npsn', 'nama', 'kelas', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat'

        $tanggal_lahir = $row['tanggal_lahir'];

        if (is_numeric($tanggal_lahir)) {
            // Convert serial date Excel ke format Y-m-d
            $tanggal_lahir = Date::excelToDateTimeObject($tanggal_lahir)->format('Y-m-d');
        } else {
            $tanggal_lahir = date('Y-m-d', strtotime($tanggal_lahir));
        }

        return new Siswa([
            'nisn'          => $row['nisn'],
            'npsn'          => $row['npsn'],
            'nama'          => $row['nama'],
            'kelas'         => $row['kelas'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir'  => $row['tempat_lahir'],
            'tanggal_lahir' => $tanggal_lahir,
            'alamat'        => $row['alamat'] ?? null,
        ]);
    }
}
