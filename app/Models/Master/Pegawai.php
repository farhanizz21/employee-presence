<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Absensi;
use App\Models\Master\Grup;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'nama',
        'telepon',
        'grup_uuid',
        'jabatan_uuid',
        'grup_sb',
        'alamat',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'pegawai_uuid', 'uuid');
    }

    public function gajians()
    {
        return $this->hasMany(Gajian::class, 'pegawai_uuid', 'uuid');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_uuid', 'uuid');
    }

    public function grupSb()
    {
        return $this->belongsTo(Grup::class, 'grup_sb', 'uuid');
    }

    public function pegawais()
{
    return $this->hasMany(\App\Models\Master\Pegawai::class, 'grup_sb', 'uuid');
}

}
