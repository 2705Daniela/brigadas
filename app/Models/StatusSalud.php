<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusSalud extends Model
{
    use HasFactory;

    protected $table = 'status_salud'; // Asegúrate que coincida con tu migración
    protected $fillable = [
        'brigadista_id',
        'cardiovascular',
        'cardiovascular_detalle',
        'hipertension',
        'hipertension_detalle',
        'diabetes',
        'diabetes_detalle',
        'epilepsia',
        'epilepsia_detalle',
        'trastornos_mentales',
        'trastornos_mentales_detalle',
        'trastornos_visuales',
        'trastornos_visuales_detalle',
        'ets',
        'ets_detalle',
        'hemorragia_interna',
        'hemorragia_interna_detalle',
        'hepatitis',
        'hepatitis_detalle',
        'otra',
        'otra_detalle',
        'operacion',
        'operacion_detalle',
        'examenes',
        'examenes_detalle',
        'medicamento_actual',
        'medicamento_actual_detalle',
        'embarazo',
        'embarazo_detalle',
        'alergias',
        'alergias_detalle',
        'tratamiento',
        'tratamiento_detalle',
        'grupo_sanguineo',
        'informacion_adicional',
        'fecha_status',
    ];

    public function brigadista()
    {
        return $this->belongsTo(Brigadista::class);
    }
}
