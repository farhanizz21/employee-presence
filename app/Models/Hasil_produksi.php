<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Hasil_produksi extends Model
{
    protected $table = 'hasil_produksi';
    protected $fillable = ['tanggal','hasil_pagi','hasil_malam'];

    public $incrementing = false;
    protected $keyType = 'string';
}
