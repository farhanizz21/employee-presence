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
        'pegawai_uuid',
        'jabatan_uuid',
        'gaji_pokok',
        'bonus_kehadiran',
        'bonus_lembur',
        'total_potongan',
        'total_gaji',
        'jumlah_hadir',
        'jumlah_lembur',
        'jumlah_telat',
        'jumlah_alpha',
        'keterangan',
    ];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_uuid', 'uuid');
    }
    public function details() {
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
}