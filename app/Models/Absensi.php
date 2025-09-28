<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\Grup;

class Absensi extends Model
{
    use SoftDeletes;
    protected $table = 'absensis';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'pegawai_uuid',
        'grup_uuid',
        'jabatan_uuid',
        'shift',
        'periode_uuid',
        'status',
        'pencapaian',
        'tgl_absen',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_uuid', 'uuid');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_uuid', 'uuid');
    }

    public function grup()
    {
        return $this->belongsTo(Grup::class, 'grup_uuid', 'uuid');
    }

    public function periode()
    {
        return $this->belongsTo(AbsensiPeriode::class, 'periode_uuid', 'uuid');
    }

    public function hasil()
    {
        return $this->belongsTo(Hasil_produksi::class, 'tgl_absen', 'tanggal');
    }

    public function hasilProduksi()
{
    return $this->hasOne(Hasil_produksi::class, 'tanggal', 'tanggal');
}


}