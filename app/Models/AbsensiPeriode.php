<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPeriode extends Model
{
    use HasFactory;

    protected $table = 'absensi_periode';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'periode_uuid', 'uuid');
    }
}
