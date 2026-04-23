<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Absensi;

class ProduksiHarian extends Model
{
    use SoftDeletes;
    protected $table = 'Produksi_harians';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'tanggal',
        'shift',
        'grup_uuid',
        'mesin_status',
        'total_produksi',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'produksi_uuid', 'uuid');
    }

}