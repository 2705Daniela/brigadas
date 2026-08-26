<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brigada;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
class BrigadaController extends Controller
{
    public function create()
    {
        return view('admin.crear_brigada'); // apunta a la ruta correcta
    }

    public function store(Request $request)
{
    $request->validate([
        'brigada' => 'required|in:Primavera,Verano,Otoño,Emergente,Extraordinaria',
        'año' => 'required|digits:4|integer|min:2000',
        'periodo_inicio' => 'required|date',
        'periodo_fin' => 'required|date|after_or_equal:periodo_inicio',
        'estado' => 'required|boolean',
    ]);

    $brigada = Brigada::create($request->all());

    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => null,
        'accion' => 'Creó brigada',
        'observaciones' => 'Brigada creada: ' .
            $brigada->brigada .
            ' (' . $brigada->año . ')' .
            ' | Periodo: ' .
            $brigada->periodo_inicio .
            ' al ' .
            $brigada->periodo_fin,
    ]);

    return redirect()
        ->route('admin.gestionar_brigada')
        ->with('success', 'Brigada creada correctamente.');
}

    public function gestionar()
    {
        $brigadas = Brigada::orderBy('año', 'desc')->get();
        return view('admin.gestionar_brigada', compact('brigadas'));
    }

// Mostrar formulario de edición
    public function edit($id)
    {
        $brigada = Brigada::findOrFail($id);
        return view('admin.editar_brigada', compact('brigada'));
    }

    // Procesar la actualización
    public function update(Request $request, $id)
{
    $request->validate([
        'brigada' => 'required|string',
        'año' => 'required|integer',
        'periodo_inicio' => 'required|date',
        'periodo_fin' => 'required|date|after_or_equal:periodo_inicio',
    ]);

    $brigada = Brigada::findOrFail($id);

    $brigada->brigada = $request->brigada;
    $brigada->año = $request->año;
    $brigada->periodo_inicio = $request->periodo_inicio;
    $brigada->periodo_fin = $request->periodo_fin;
    $brigada->estado = $request->has('estado') ? 1 : 0;
    $brigada->save();

    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => null,
        'accion' => 'Editó brigada',
        'observaciones' => 'Brigada modificada: ' .
            $brigada->brigada .
            ' (' . $brigada->año . ')' .
            ' | Periodo: ' .
            $brigada->periodo_inicio .
            ' al ' .
            $brigada->periodo_fin,
    ]);

    return redirect()
        ->route('admin.gestionar_brigada')
        ->with('success', 'Brigada actualizada correctamente.');
}

}
