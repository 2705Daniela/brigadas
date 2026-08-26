<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brigadista;
use App\Models\EstadoMunicipio;
use App\Models\Supervisor;

class FiltrosController extends Controller
{
   public function filtrar(Request $request)
{
    $query = Brigadista::whereHas('registros.brigada', function($q) {
        $q->where('estado', 1); 
    })->with(['registros.brigada', 'bitacoras.usuario']);
    // FILTROS
    if ($request->filled('numero_boleta')) {
        $query->where('numero_boleta', 'like', '%'.$request->numero_boleta.'%');
    }

    if ($request->filled('unidad_academica')) {
        $query->where('unidad_academica', $request->unidad_academica);
    }

    if ($request->filled('programa_academico')) {
        $query->where('programa_academico', $request->programa_academico);
    }

    $brigadistas = $query->get();

    // HTML dinámico
    $html = '';
    foreach ($brigadistas as $brigadista) {
        // Brigadas activas
        $brigadas = '';
        foreach ($brigadista->registros as $registro) {
            if ($registro->brigada && $registro->brigada->estado == 1) {
                $brigadas .= '<span class="badge bg-info">'
                           . e($registro->brigada->brigada)
                           . ' (' . e($registro->brigada->año) . ')</span> ';
            }
        }

        // Acciones sin <form>, usando JS para eliminar
        $acciones = '
            <a href="'.route('admin.brigadistas.mostrarValidar', $brigadista->id).'"
               class="action-link text-success me-2">Ver</a>
            <a href="'.route('admin.brigadistas.eliminar', $brigadista->id).'"
               class="action-link text-danger"
               onclick="event.preventDefault(); eliminarBrigadista('.$brigadista->id.');">
               Eliminar
            </a>
        ';

        // Determinar estatus
        $registro = $brigadista->registros->last();
        $status = $registro->status ?? null;

         if ($status === 'validado') {
         $estatus = '<span class="badge bg-success">Validado</span>';
         } elseif ($status === 'cancelado') {
         $estatus = '<span class="badge bg-danger">Cancelado</span>';
         } elseif ($status === 'rechazado') {
        $estatus = '<span class="badge bg-warning text-dark">Rechazado</span>';
         } else {
        $estatus = '<span class="badge bg-secondary">Pendiente</span>';
         }


        $html .= '<tr>
            <td><input type="checkbox" name="brigadistas[]" value="'.$brigadista->id.'" class="select-brigadista"></td>
            <td>'.$brigadista->id.'</td>
            <td>'.$brigadista->numero_boleta.'</td>
            <td>'.e($brigadista->nombre.' '.$brigadista->apellido_paterno.' '.$brigadista->apellido_materno).'</td>
            <td>'.e($brigadista->unidad_academica).'</td>
            <td>'.e($brigadista->programa_academico).'</td>
            <td>'.$brigadas.'</td>
            <td>'.$brigadista->created_at->format('d/m/Y').'</td>
            <td>'.$acciones.'</td>
            <td>'.e($brigadista->bitacoras->last()->usuario->name ?? '-').'</td>
            <td>'.$estatus.'</td>
        </tr>';
    }

    return $html;
}

     public function filtrarRegistrosCompletos(Request $request)
    {
        $query = Brigadista::query()->with(['registros.brigada', 'bitacoras.usuario']);

        // FILTROS
        if ($request->filled('numero_boleta')) {
            $query->where('numero_boleta', 'like', '%'.$request->numero_boleta.'%');
        }

        if ($request->filled('unidad_academica')) {
            $query->where('unidad_academica', $request->unidad_academica);
        }

        if ($request->filled('programa_academico')) {
            $query->where('programa_academico', $request->programa_academico);
        }

        if ($request->filled('brigada_id')) {
        $query->whereHas('registros', function($q) use ($request) {$q->where('brigada_id', $request->brigada_id);});
        } 

        $brigadistas = $query->get();

        // HTML dinámico
        $html = '';
        foreach ($brigadistas as $brigadista) {
            $brigadas = '';
            foreach ($brigadista->registros as $registro) {
                if ($registro->brigada) { 
                   $brigadas .= '<span class="badge bg-info">'
                   . e($registro->brigada->brigada)
                   . ' (' . e($registro->brigada->año) . ')</span> ';
                }
            }

            // Acciones sin <form>, usando JS para eliminar
            $acciones = '
                <a href="'.route('admin.brigadistas.mostrarValidar', $brigadista->id).'"
                   class="action-link text-success me-2">Ver</a>
                <a href="'.route('admin.brigadistas.eliminar', $brigadista->id).'"
                   class="action-link text-danger"
                   onclick="event.preventDefault(); eliminarBrigadista('.$brigadista->id.');">
                   Eliminar
                </a>
            ';

            // Determinar estatus
        $registro = $brigadista->registros->last();
        $status = $registro->status ?? null;

         if ($status === 'validado') {
         $estatus = '<span class="badge bg-success">Validado</span>';
         } elseif ($status === 'cancelado') {
         $estatus = '<span class="badge bg-danger">Cancelado</span>';
         } elseif ($status === 'rechazado') {
        $estatus = '<span class="badge bg-warning text-dark">Rechazado</span>';
         } else {
        $estatus = '<span class="badge bg-secondary">Pendiente</span>';
         }


            $html .= '<tr>
                <td><input type="checkbox" name="brigadistas[]" value="'.$brigadista->id.'" class="select-brigadista"></td>
                <td>'.$brigadista->id.'</td>
                <td>'.$brigadista->numero_boleta.'</td>
                <td>'.e($brigadista->nombre.' '.$brigadista->apellido_paterno.' '.$brigadista->apellido_materno).'</td>
                <td>'.e($brigadista->unidad_academica).'</td>
                <td>'.e($brigadista->programa_academico).'</td>
                <td>'.$brigadas.'</td>
                <td>'.$brigadista->created_at->format('d/m/Y').'</td>
                <td>'.$acciones.'</td>
                <td>'.e($brigadista->bitacoras->last()->usuario->name ?? '-').'</td>
                <td>'.$estatus.'</td>
            </tr>';
        }

        return $html;
    }
public function filtrarAsignaciones(Request $request)
{
    // Consultar brigadistas con registros de brigada activas
    $query = Brigadista::whereHas('registros.brigada', function($q) {
        $q->where('estado', 1); 
    })->with(['registros.brigada', 'bitacoras.usuario', 'asignaciones']);

    // FILTROS
    if ($request->filled('numero_boleta')) {
        $query->where('numero_boleta', 'like', '%' . $request->numero_boleta . '%');
    }

    if ($request->filled('unidad_academica')) {
        $query->where('unidad_academica', $request->unidad_academica);
    }

    if ($request->filled('programa_academico')) {
        $query->where('programa_academico', $request->programa_academico);
    }

    $brigadistas = $query->get();

    // Estados y proyectos
    $estados = EstadoMunicipio::select('estado')->distinct()->orderBy('estado')->get();
    $proyectos = [
        "Atención integral a la salud",
        "Levantamiento de poligonales cerradas",
        "Diagnóstico de ordenamiento vial",
        "Proyecto de vialidades (calles y caminos)",
        "Diseño arquitectónico",
        "Instalación de análisis de sistemas de bombeo",
        "Línea de conducción de agua potable",
        "Captación de agua pluvial para sistema de riego",
        "Reconocimiento de plantas endémicas a través de visión artificial",
        "Mantenimiento correctivo y preventivo de sistemas de computo",
        "Diseño de página web",
        "Conservación de conocimientos tradicionales mediante IA",
        "Proyecto de desarrollo turístico",
        "Rescate de archivos históricos municipales",
        "Evaluación de servicios públicos bibliotecarios",
        "Disponibilidad y uso de materiales en bibliotecas públicas",
        "Capacitación para compostas y huertos comunitarios",
        "Proyecto productivo de bebidas y alimentos"
    ];

    // HTML dinámico
    $html = '';
    foreach ($brigadistas as $brigadista) {
        // Asegurar que traemos la última asignación válida
        $asignacion = $brigadista->asignaciones->sortByDesc('updated_at')->first();

        $estadoSeleccionado = trim($asignacion->estado_asignado ?? '');
        $municipioSeleccionado = trim($asignacion->municipio_asignado ?? '');
        $proyectoSeleccionado = trim($asignacion->proyecto_asignado ?? '');

        // Bloquear selects si ya hay asignación
        $disabled = $asignacion ? 'disabled' : '';

        $html .= '<tr data-brigadista-id="'.$brigadista->id.'">';
        $html .= '<td>'.$brigadista->id.'</td>';
        $html .= '<td>'.$brigadista->numero_boleta.'</td>';
        $html .= '<td>'.e($brigadista->nombre.' '.$brigadista->apellido_paterno.' '.$brigadista->apellido_materno).'</td>';
        $html .= '<td>'.e($brigadista->programa_academico).'</td>';
        $html .= '<td>'.e($brigadista->unidad_academica).'</td>';

        // Select Estado
        $html .= '<td><select class="form-select estado-select" '.$disabled.'>';
        $html .= '<option value="">Selecciona estado</option>';
        foreach ($estados as $estado) {
            $selected = ($estado->estado == $estadoSeleccionado) ? 'selected' : '';
            $html .= '<option value="'.$estado->estado.'" '.$selected.'>'.$estado->estado.'</option>';
        }
        $html .= '</select></td>';

        // Select Municipio
        $html .= '<td><select class="form-select municipio-select" data-selected="'.$municipioSeleccionado.'" '.$disabled.'>';
        $html .= '<option value="">Selecciona municipio</option>';
        if ($estadoSeleccionado) {
            $municipios = EstadoMunicipio::where('estado', $estadoSeleccionado)
                            ->orderBy('municipio')
                            ->pluck('municipio');
            foreach ($municipios as $mun) {
                $selected = ($mun == $municipioSeleccionado) ? 'selected' : '';
                $html .= '<option value="'.$mun.'" '.$selected.'>'.$mun.'</option>';
            }
        }
        $html .= '</select></td>';

     
        $html .= '<td><select class="form-select proyecto-select" '.$disabled.'>';
        $html .= '<option value="">-- Selecciona un proyecto --</option>';
        foreach ($proyectos as $p) {
            $selected = (trim($p) == $proyectoSeleccionado) ? 'selected' : '';
            $html .= '<option value="'.$p.'" '.$selected.'>'.$p.'</option>';
        }
        $html .= '</select></td>';


        // Botones según estado de asignación
        if ($asignacion) {
            $html .= '<td>
                        <button type="button" class="btn btn-link text-success btn-guardar d-none">Guardar</button>
                        <button type="button" class="btn btn-link text-warning btn-editar">Editar</button>
                      </td>';
        } else {
            $html .= '<td>
                        <button type="button" class="btn btn-link text-success btn-guardar">Guardar</button>
                        <button type="button" class="btn btn-link text-warning btn-editar d-none">Editar</button>
                      </td>';
        }

        $html .= '</tr>';
    }

    return $html;
}

    public function filtrarListaAsistencia(Request $request)
{
    // === FILTRO DE BRIGADISTAS ===
    $brigadistasQuery = Brigadista::with(['registros.brigada', 'asignaciones']);

    if ($request->filled('municipio')) {
        $brigadistasQuery->whereHas('asignaciones', function($q) use ($request) {
            $q->where('municipio_asignado', 'like', '%'.$request->municipio.'%');
        });
    }

    $brigadistas = $brigadistasQuery->get();

    $brigadistasHtml = '';
    foreach ($brigadistas as $brigadista) {
        $asignacion = $brigadista->asignaciones->first();
        $rol = $brigadista->registros->first()->rol_deseado ?? '-';

        $brigadistasHtml .= '<tr>
            <td>'.e($brigadista->numero_boleta).'</td>
            <td>'.e($brigadista->nombre.' '.$brigadista->apellido_paterno.' '.$brigadista->apellido_materno).'</td>
            <td>'.e($brigadista->unidad_academica).'</td>
            <td>'.e($brigadista->programa_academico).'</td>
            <td>'.e($brigadista->telefono).'</td>
            <td>'.e($brigadista->correo).'</td>
            <td>'.e($asignacion->municipio_asignado ?? '-').'</td>
            <td>'.e($asignacion->proyecto_asignado ?? '-').'</td>
            <td>'.e($rol).'</td>
        </tr>';
    }

    if ($brigadistasHtml === '') {
        $brigadistasHtml = '<tr><td colspan="9">No se encontraron brigadistas con esos filtros.</td></tr>';
    }

    // === FILTRO DE SUPERVISORES ===
    $supervisoresQuery = Supervisor::with('asignaciones');

    if ($request->filled('municipio')) {
        $supervisoresQuery->whereHas('asignaciones', function($q) use ($request) {
            $q->where('municipio_asignado', 'like', '%'.$request->municipio.'%');
        });
    }

    $supervisores = $supervisoresQuery->get();

    $supervisoresHtml = '';
    foreach ($supervisores as $supervisor) {
        $supervisoresHtml .= '<tr>
            <td>'.e($supervisor->nombre).'</td>
            <td>'.e($supervisor->correo).'</td>
            <td>'.e($supervisor->telefono).'</td>
            <td>'.e($supervisor->asignaciones->pluck('municipio_asignado')->join(', ') ?: '-').'</td>
            <td>SUPERVISOR</td>
        </tr>';
    }

    if ($supervisoresHtml === '') {
        $supervisoresHtml = '<tr><td colspan="6">No hay supervisores con esos filtros.</td></tr>';
    }

    // === RESPUESTA JSON ===
    return response()->json([
        'brigadistasHtml' => $brigadistasHtml,
        'supervisoresHtml' => $supervisoresHtml,
    ]);
}
public function filtrarListaBrigadistas(Request $request)
{
    $query = Brigadista::query();

    if ($request->filled('numero_boleta')) {
        $query->where('numero_boleta', 'like', '%' . $request->numero_boleta . '%');
    }

    if ($request->filled('nombre_completo')) {
        $nombre = $request->nombre_completo;
        $query->where(function ($q) use ($nombre) {
            $q->where('nombre', 'like', "%$nombre%")
              ->orWhere('apellido_paterno', 'like', "%$nombre%")
              ->orWhere('apellido_materno', 'like', "%$nombre%");
        });
    }

    $brigadistas = $query->get();

    if ($request->ajax()) {
        // Solo devolver las filas HTML
        $html = '';
        foreach ($brigadistas as $brigadista) {
            $html .= '
                <tr>
                    <td>' . e($brigadista->id) . '</td>
                    <td>' . e($brigadista->nombre . ' ' . $brigadista->apellido_paterno . ' ' . $brigadista->apellido_materno) . '</td>
                    <td>' . e($brigadista->numero_boleta) . '</td>
                    <td>' . e($brigadista->correo) . '</td>
                    <td>
                        <a href="' . route('admin.brigadistas.restablecer', $brigadista->id) . '" class="btn btn-sm btn-info">Restablecer</a>
                        <form action="' . route('admin.brigadistas.eliminar', $brigadista->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>';
        }
        return $html;
    }

    // Si no es AJAX, devolver la vista completa
    return view('admin.lista_brigadistas', compact('brigadistas'));
}
public function filtrarSupervisores(Request $request)
{
    $query = Supervisor::with(['asignaciones']);

    if ($request->filled('nombre')) {
        $nombre = trim($request->nombre);

        $query->where(function ($q) use ($nombre) {
            $q->whereRaw("LOWER(nombre) LIKE ?", ['%' . strtolower($nombre) . '%'])
              ->orWhereRaw("LOWER(apellido_paterno) LIKE ?", ['%' . strtolower($nombre) . '%'])
              ->orWhereRaw("LOWER(apellido_materno) LIKE ?", ['%' . strtolower($nombre) . '%']);
        });
    }

    $supervisores = $query->get();

    $estados = EstadoMunicipio::select('estado')
        ->distinct()
        ->orderBy('estado')
        ->get();

    $html = '';

    foreach ($supervisores as $supervisor) {

        $asignacion = $supervisor->asignaciones
            ->sortByDesc('updated_at')
            ->first();

        $estadoSeleccionado = trim($asignacion->estado_asignado ?? '');
        $municipioSeleccionado = trim($asignacion->municipio_asignado ?? '');

        $disabled = $asignacion ? 'disabled' : '';

        $html .= '<tr data-supervisor-id="'.$supervisor->id.'">';

        $html .= '<td>'.e($supervisor->id).'</td>';

        $html .= '<td>'.e(
            $supervisor->nombre.' '.
            $supervisor->apellido_paterno.' '.
            $supervisor->apellido_materno
        ).'</td>';

        $html .= '<td>'.e($supervisor->correo).'</td>';

        $html .= '<td>'.e($supervisor->telefono).'</td>';

        $html .= '<td>
            <select class="form-select estado-select" '.$disabled.'>
                <option value="">Selecciona estado</option>';

        foreach ($estados as $estado) {
            $selected = ($estado->estado == $estadoSeleccionado)
                ? 'selected'
                : '';

            $html .= '<option value="'.e($estado->estado).'" '.$selected.'>'
                .e($estado->estado).
            '</option>';
        }

        $html .= '</select>
        </td>';

        $html .= '<td>
            <select
                class="form-select municipio-select"
                data-selected="'.e($municipioSeleccionado).'"
                '.$disabled.'>
                <option value="">Selecciona municipio</option>';

        if ($estadoSeleccionado) {

            $municipios = EstadoMunicipio::where(
                'estado',
                $estadoSeleccionado
            )
            ->orderBy('municipio')
            ->pluck('municipio');

            foreach ($municipios as $municipio) {

                $selected = ($municipio == $municipioSeleccionado)
                    ? 'selected'
                    : '';

                $html .= '<option value="'.e($municipio).'" '.$selected.'>'
                    .e($municipio).
                '</option>';
            }
        }

        $html .= '</select>
        </td>';


        if ($asignacion) {

            $html .= '<td>
                <button
                    type="button"
                    class="btn btn-link text-success btn-guardar d-none">
                    Guardar
                </button>

                <button
                    type="button"
                    class="btn btn-link text-warning btn-editar">
                    Editar
                </button>
            </td>';

        } else {

            $html .= '<td>
                <button
                    type="button"
                    class="btn btn-link text-success btn-guardar">
                    Guardar
                </button>

                <button
                    type="button"
                    class="btn btn-link text-warning btn-editar d-none">
                    Editar
                </button>
            </td>';
        }

        $html .= '</tr>';
    }

    return $html;
}

}