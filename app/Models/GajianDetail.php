<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajianDetail extends Model {
    public function gajian() {
        return $this->belongsTo(Gajian::class, 'gajian_uuid', 'uuid');
    }
}

