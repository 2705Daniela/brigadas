<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

   
    protected $table = 'asignaciones';

    protected $fillable = [
        'brigadista_id',
        'brigada_id',
        'supervisor_id',
        'municipio_asignado',
        'estado_asignado',
        'proyecto_asignado',
        'publicado'
    ];

    public function brigadista()
    {
        return $this->belongsTo(Brigadista::class);
    }

    public function estadoMunicipio()
{
    return $this->belongsTo(EstadoMunicipio::class, 'municipio_asignado', 'municipio');

}

    public function supervisor()
{
    return $this->belongsTo(Supervisor::class);
}

}

