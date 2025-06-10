<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keuangan extends Model
{
    use HasFactory;
    // Table name
    protected $table = 'keuangan';
    protected $keyType = 'string';
    public $incrementing = false;


    // Mass assignable attributes
    protected $fillable = [
        'id',
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

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        if (empty($model->id)) {
            $model->id = (string) Str::uuid();
        }
    });
}
}
