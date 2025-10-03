<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPeriode extends Model
{
    use HasFactory;

    protected $table = 'absensi_periode';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'periode_uuid', 'uuid');
    }

    public function getLabelAttribute()
    {
        $start = \Carbon\Carbon::parse($this->tanggal_mulai)->translatedFormat('d M Y');
        $end = \Carbon\Carbon::parse($this->tanggal_selesai)->translatedFormat('d M Y');
        return "$start s/d $end";
    }
}

