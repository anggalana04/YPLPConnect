<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    // Table name
    protected $table = 'guru';

    // Primary key
    protected $primaryKey = 'nuptk';
    public $incrementing = false;
    protected $keyType = 'string';

    // Mass assignable attributes
    protected $fillable = [
        'nuptk',
        'npsn', // Add this to match the migration and enable relationship
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
