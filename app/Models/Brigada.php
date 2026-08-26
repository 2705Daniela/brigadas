<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Brigada extends Model
{
    use HasFactory;

    protected $fillable = [
        'brigada',
        'año',
        'periodo_inicio',
        'periodo_fin',
        'estado',
    ];
 public function registros()
{
    return $this->hasMany(Registro::class);
}

}


