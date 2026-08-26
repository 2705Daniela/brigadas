<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google2fa_secret',
    ];

    // Campos que se ocultan en arrays o JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Campos que se deben convertir a otro tipo
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // User.php
public function brigadista()
{
    return $this->hasOne(Brigadista::class);
}

}

