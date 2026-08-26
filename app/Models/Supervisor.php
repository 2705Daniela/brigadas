<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;
    protected $table = 'supervisores';
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'curp',
        'correo',
        'telefono',
        'clabe',
        'banco',
        'brigada_id',
    ];

    public function brigadas()
    {
        return $this->hasMany(Brigada::class);
    }

    public function asignaciones()
{
    return $this->hasMany(Asignacion::class);
}

}

