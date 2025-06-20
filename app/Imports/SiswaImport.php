<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // jika kamu pakai heading row di Excel
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class SiswaImport implements ToModel, WithHeadingRow, WithEvents
{
    use SkipsFailures;
    /**
     * Map setiap baris Excel ke model Siswa
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $errorMessage = null;
    public static $requiredColumns = [
        'nisn', 'npsn', 'nama', 'kelas', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat'
    ];

    public static function beforeImport(BeforeImport $event)
    {
        $sheet = $event->getReader()->getActiveSheet();
        $headings = $sheet->toArray()[0];
        $headings = array_map('strtolower', $headings);
        $missing = array_diff(self::$requiredColumns, $headings);
        $extra = array_diff($headings, self::$requiredColumns);
        if (count($missing) > 0) {
            throw new \Exception('Kolom berikut wajib ada di file Excel: ' . implode(', ', $missing));
        }
        if (count($extra) > 0) {
            throw new \Exception('Kolom pada file excel hanya 
            (nisn, npsn, nama, kelas, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat)');
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
