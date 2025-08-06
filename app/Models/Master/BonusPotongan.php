<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusPotongan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'nama',
        'jenis', // 1=Bonus, 2=Potongan
        'nominal', // Jumlah nominal
        'keterangan', // Deskripsi atau keterangan
        'status', // 1=Aktif, 2=Nonaktif
        'jabatan', // UUID Jabatan yang terkait
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            1 => 'Aktif',
            2 => 'Nonaktif',
            default => 'Tidak diketahui',
        };
    }

    
    public function getStatusClassAttribute()
    {
        return $this->status == 1 ? 'success' : 'danger';
    }

    public function getJenisLabelAttribute()
    {
        return match ($this->jenis) {
            1 => 'Bonus',
            2 => 'Potongan',
            default => 'Tidak diketahui',
        };
    }
}