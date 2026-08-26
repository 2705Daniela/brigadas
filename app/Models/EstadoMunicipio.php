<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoMunicipio extends Model
{
    use HasFactory;

    protected $table = 'estados_municipios';

    protected $fillable = [
        'estado',
        'municipio'
    ];
}


