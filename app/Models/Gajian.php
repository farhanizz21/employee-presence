<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;

class Gajian extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'periode_uuid',
        'pegawai_uuid',
        
        'hadir',
        'izin',
        'alpha',

        'gaji_pokok',
        'bonus',
        'potongan',
        
        'gaji_bersih',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_uuid', 'uuid');
    }
    public function details()
    {
        return $this->hasMany(GajianDetail::class, 'gajian_uuid', 'uuid');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_uuid', 'uuid');
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function periode()
    {
        return $this->belongsTo(AbsensiPeriode::class, 'periode_uuid', 'uuid');
    }

    public function absensiPeriode()
{
    return $this->belongsTo(AbsensiPeriode::class, 'periode_uuid', 'uuid');
}
}