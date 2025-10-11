<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grups';
    protected $fillable = ['uuid', 'nama'];

    // Relasi ke pegawai
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'grup_sb', 'uuid');
    }
}
