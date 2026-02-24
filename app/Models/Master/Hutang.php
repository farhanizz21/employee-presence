<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'pegawai_uuid',
        'nominal',
        'is_active',
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