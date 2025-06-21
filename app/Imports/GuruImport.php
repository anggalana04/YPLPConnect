<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;

class GuruImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $header = array_map('strtolower', $rows[0]->toArray());

        foreach ($rows->skip(1) as $row) {
            $rowData = array_combine($header, $row->toArray());

            if (!isset($rowData['nuptk'], $rowData['npa'], $rowData['nama'], $rowData['jenis_kelamin'],
                $rowData['tempat_lahir'], $rowData['tanggal_lahir'], $rowData['alamat'], $rowData['no_hp'])) {
                continue;
            }

            if (Guru::where('nuptk', $rowData['nuptk'])->exists()) {
                continue;
            }

            // Konversi tanggal_lahir jika berupa angka serial Excel
            $tanggal_lahir = $rowData['tanggal_lahir'];
            if (is_numeric($tanggal_lahir)) {
                $date = Date::excelToDateTimeObject($tanggal_lahir);
                $tanggal_lahir = $date->format('Y-m-d');
            }

            Guru::create([
                'nuptk' => $rowData['nuptk'],
                'npa' => $rowData['npa'],
                'nama' => $rowData['nama'],
                'jenis_kelamin' => $rowData['jenis_kelamin'],
                'tempat_lahir' => $rowData['tempat_lahir'],
                'tanggal_lahir' => $tanggal_lahir,
                'alamat' => $rowData['alamat'],
                'no_hp' => $rowData['no_hp'],
                'npsn' => Auth::user()->npsn,
                'status' => 'Aktif',
            ]);
        }
    }
}