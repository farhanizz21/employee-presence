<?php

namespace App\Models\Master;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'username',
        'email',
        'password',
        'pegawai_uuid',
        'role',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_uuid', 'uuid');
    }

    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            1 => 'Admin',
            2 => 'User',
            default => 'Tidak diketahui',
        };
    }
}