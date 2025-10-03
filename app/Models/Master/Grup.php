<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Absensi;

class Grup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'grup',
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
        return $this->hasMany(Pegawai::class, 'grup_uuid', 'uuid');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'grup_uuid', 'uuid');
    }
}
