<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brigadista;
use App\Models\StatusSalud;

class StatusSaludController extends Controller
{
    public function mostrarStatusSalud($id)
    {
        $brigadista = Brigadista::findOrFail($id);
        return view('brigadistas.status_salud', compact('brigadista'));
    }

    public function guardarStatusSalud(Request $request, $id)
{
    $brigadista = Brigadista::findOrFail($id);

    $status = new StatusSalud();
    $status->brigadista_id = $brigadista->id;

    $campos = [
        'cardiovascular', 'cardiovascular_detalle',
        'hipertension', 'hipertension_detalle',
        'diabetes', 'diabetes_detalle',
        'epilepsia', 'epilepsia_detalle',
        'trastornos_mentales', 'trastornos_mentales_detalle',
        'trastornos_visuales', 'trastornos_visuales_detalle',
        'ets', 'ets_detalle',
        'hemorragia_interna', 'hemorragia_interna_detalle',
        'hepatitis', 'hepatitis_detalle',
        'otra', 'otra_detalle',
        'operacion', 'operacion_detalle',
        'examenes', 'examenes_detalle',
        'medicamento_actual', 'medicamento_actual_detalle',
        'embarazo', 'embarazo_detalle',
        'alergias', 'alergias_detalle',
        'tratamiento', 'tratamiento_detalle',
        'grupo_sanguineo', 'informacion_adicional',
        'fecha_status'
    ];

    foreach ($campos as $campo) {
        $status->$campo = $request->input($campo);
    }

    $status->save();

    // **Actualizar status_completado del brigadista**
    $brigadista->status_completado = true;
    $brigadista->save();

    return redirect()->route('brigadistas.acciones', $brigadista->id)
        ->with('success', 'Status de salud guardado correctamente.');
}
}
