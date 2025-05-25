<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';
    protected $primaryKey = 'id';  // primary key sekarang 'id'
    public $incrementing = false;  // karena manual string, bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nuptk',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_unit_kerja',
        'jenis_sk',
        'status',
        'file_path',
        'submitted_by',
        'verified_by',
        'verified_at',
        'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
    ];

    // relasi seperti semula, misal:
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'nuptk', 'nuptk');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $date = now()->format('dmy');
                $prefix = "PJ{$date}";

                $lastDoc = DB::table('dokumen')
                    ->where('id', 'like', "{$prefix}%")
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastDoc) {
                    $lastNumber = (int) substr($lastDoc->id, -4);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }

                $model->id = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
