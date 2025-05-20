<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    // Table name
    protected $table = 'sekolah';

    // Primary key
    protected $primaryKey = 'npsn';
    public $incrementing = false;
    protected $keyType = 'string';

    // Mass assignable attributes
    protected $fillable = [
        'npsn',
        'nama',
        'jenjang',
        'alamat',
        'email',
    ];

    // Sekolah has many Keuangan
    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'npsn', 'npsn');
    }

    // Sekolah has many Guru
    public function guru()
    {
        return $this->hasMany(Guru::class, 'npsn', 'npsn');
    }
}
