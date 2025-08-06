<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Master\Pegawai;

class Absensi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'pegawai_uuid',
        'grup_uuid',
        'status', // 1=Hadir, 2=lembur, 3=telat, 4=Alfa
        'tgl_absen',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_uuid', 'uuid');
    }
}