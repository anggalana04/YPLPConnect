<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengajuan',
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
            if (empty($model->id_pengajuan)) {
                $date = now()->format('dmy');
                $prefix = "PG-{$date}-";
                $lastDoc = DB::table('dokumen')
                    ->where('id_pengajuan', 'like', "{$prefix}%")
                    ->orderBy('id_pengajuan', 'desc')
                    ->first();
                $number = $lastDoc ? (int) substr($lastDoc->id_pengajuan, -4) + 1 : 1;
                $model->id_pengajuan = $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}