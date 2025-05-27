<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    // Table name
    protected $table = 'keuangan';

    // Mass assignable attributes
    protected $fillable = [
        'npsn',
        'tahun',
        'bulan',
        'jumlah_spp',
        'status',
        'bukti_path',
        'verified_by',
        'verified_at',
        'catatan',
    ];

    // Casts
    protected $casts = [
        'verified_at' => 'datetime',
        'jumlah_spp' => 'decimal:2',
    ];

    // Keuangan belongs to Sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'npsn', 'npsn');
    }

    // Keuangan verified by User
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
