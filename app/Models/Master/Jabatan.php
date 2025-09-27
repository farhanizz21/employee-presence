<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'jabatan',
        'gaji',
        'harian',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getHarianTextAttribute()
    {
        return match ($this->harian) {
            1 => 'Harian',
            2 => 'Borongan',
            default => 'Tidak Diketahui'
        };
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'grup_uuid', 'uuid');
    }
}
