<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasRoleSettings;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoleSettings, SoftDeletes;

    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'nim',
        'email',
        'phone_number',
        'password',
        'role',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function hasRole($role)
{
    return $this->role === $role;
}


    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function roleApplications()
    {
        return $this->hasMany(RoleApplication::class, 'user_id');
    }

    // TAMBAHKAN RELASI INI UNTUK MENGAMBIL DATA PENGAJUAN TERBARU / AKTIF
    public function latestApplication()
    {
        return $this->hasOne(RoleApplication::class, 'user_id', 'nim')->latestOfMany();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function panitiaProfile()
{
    return $this->hasOne(PanitiaProfile::class, 'user_id');
}

    public function approvedApplication()
    {
        return $this->hasOne(RoleApplication::class, 'user_id')
            ->where('status', 'approved')
            ->withDefault();
    }

}
