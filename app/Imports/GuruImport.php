<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class GuruImport implements ToModel, WithHeadingRow, WithEvents
{
    public static $requiredColumns = [
        'nuptk', 'npa', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_hp'
    ];

    public static function beforeImport(BeforeImport $event)
    {
        $sheet = $event->getReader()->getActiveSheet();
        $headings = $sheet->toArray()[0];
        $normalize = function($h) {
            $h = preg_replace('/\s+/u', '', $h);
            $h = preg_replace('/[^a-zA-Z0-9_]/u', '', $h);
            return strtolower($h);
        };
        $headings = array_map($normalize, $headings);
        $required = array_map($normalize, self::$requiredColumns);
        $missing = array_diff($required, $headings);
        $extra = array_diff($headings, $required);
        if (count($missing) > 0) {
            // Alert error: kolom wajib, posisi tengah atas, z-index tinggi (handled in controller/view)
            throw new \Exception('Kolom berikut wajib ada di file Excel: ' . implode(', ', $missing));
        }
        if (count($extra) > 0) {
            // Alert error: kolom harus persis, posisi tengah atas, z-index tinggi (handled in controller/view)
            throw new \Exception('Kolom pada file excel hanya
            (nuptk, npa, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, no_hp)');
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => [self::class, 'beforeImport'],
        ];
    }

    public function model(array $row)
    {
        $tanggal_lahir = $row['tanggal_lahir'];
        if (is_numeric($tanggal_lahir)) {
            $tanggal_lahir = Date::excelToDateTimeObject($tanggal_lahir)->format('Y-m-d');
        }
        // Cek jika sudah ada guru dengan NUPTK yang sama
        $existing = \App\Models\Guru::where('nuptk', $row['nuptk'])->first();
        if ($existing) {
            throw new \Exception('Guru dengan NUPTK = ' . $row['nuptk'] . ' sudah ada');
        }
        return new Guru([
            'nuptk' => $row['nuptk'],
            'npa' => $row['npa'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $row['alamat'],
            'no_hp' => $row['no_hp'],
            'npsn' => Auth::user()->npsn,
            'status' => 'Aktif',
        ]);
    }
}