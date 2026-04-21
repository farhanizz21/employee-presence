<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;

class GajianPeriode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];


    public function getRouteKeyName()
    {
        return 'uuid';
    }
}