<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\BonusPotongan;


class Jabatan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'jabatan',
        'gaji_pagi',
        'gaji_malam',
        'harian',
        'bonus_uuid',
        'keterangan',
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

    public function bonusPotongan()
    {
        return $this->belongsTo(BonusPotongan::class, 'bonus_uuid', 'uuid');
    }
}