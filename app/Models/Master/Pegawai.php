<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Absensi;

class Pegawai extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'nama',
        'telepon',
        'grup_uuid',
        'jabatan_uuid',
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

    public function grup()
    {
        return $this->belongsTo(Grup::class, 'grup_uuid', 'uuid');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_uuid', 'uuid');
    }
    
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'pegawai_uuid', 'uuid');
    }
}