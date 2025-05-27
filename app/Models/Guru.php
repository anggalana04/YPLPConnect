<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    // Specify the table name (optional if model name matches table name)
    protected $table = 'guru';

    // Define the primary key
    protected $primaryKey = 'NUPTK';

    protected $fillable = [
        'NUPTK',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'status',
    ];

    // Cast fields to specific types
    protected $casts = [
        'tanggal_lahir' => 'date', // Automatically convert to Carbon instance
        'status' => 'string', // Ensure enum is treated as string
        'jenis_kelamin' => 'string', // Ensure enum is treated as string
    ];
}
