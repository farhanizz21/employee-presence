<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'nama',
        'golongan_uuid',
        'telepon',
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
}