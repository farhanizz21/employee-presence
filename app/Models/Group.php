<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;

class Grup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grups';

    protected $fillable = [
        'uuid',
        'grup',
        'pencapaian',   // ✅ tambahan
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
