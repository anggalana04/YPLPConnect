<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengaduan extends Model
{
    use HasFactory; // Tambahkan ini
    
    protected $table = 'pengaduan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'npsn',
        'judul',
        'deskripsi',
        'kategori',
        'bukti_path',
        'status',
        'submitted_by',
        'verified_by',
        'verified_at',
        'catatan',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // Pengaduan belongs to Sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'npsn', 'npsn');
    }

    // Pengaduan submitted by User
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    // Pengaduan verified by User
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
