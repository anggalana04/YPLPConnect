<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // tambahkan ini

class Guru extends Model
{
    use HasFactory; // perbaiki penulisan dan kapitalisasi

    // Table name
    protected $table = 'guru';

    // Primary key
    protected $primaryKey = 'nuptk';
    public $incrementing = false;
    protected $keyType = 'string';

    // Mass assignable attributes
    protected $fillable = [
        'nuptk',
        'npsn',
        'npa',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'status',
    ];

    // Guru belongs to a Sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'npsn', 'npsn');
    }
}
