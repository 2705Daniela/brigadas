<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlumnoController extends Controller
{
    public function buscar(Request $request)
    {
        $boleta = trim($request->input('boleta'));

        if (empty($boleta)) {
            return response()->json(['error' => 'Debes ingresar una boleta.']);
        }

        try {
            // Llamada al servicio web DESS
            $response = Http::asForm()->post('https://contsaes.sitios.dae.support/siis/dess/ws/WS_DESS.asmx/BusquedaAlumno', [
                'Escuela' => '',  
                'TipoBusqueda' => 'B',
                'ParametroBusqueda' => $boleta
            ]);

            // Obtener el contenido XML
            $xml = $response->body();

            // Extraer el JSON dentro del <string>...</string>
            preg_match('/<string[^>]*>(.*?)<\/string>/s', $xml, $matches);

            if (empty($matches[1])) {
                return response()->json(['error' => 'No se encontraron resultados.']);
            }

            // Decodificar el JSON contenido en la respuesta
            $jsonData = json_decode($matches[1], true);

            if (empty($jsonData) || !is_array($jsonData)) {
                return response()->json(['error' => 'Error al conectar con el servicio.']);
            }

            // Buscar registro con Nivel "S"
             $alumno = null;
             foreach ($jsonData as $registro) {
             if (isset($registro['Nivel']) && $registro['Nivel'] === 'S') {
             $alumno = $registro;
             break;
             }
            }

            // Si no se encuentra Nivel "S", retornar error
            if (!$alumno) {
               return response()->json([
            'error' => 'No se encontraron registros con Nivel "S". No se puede registrar.'
            ]);
            }

            return response()->json([
                'ok' => true,
                'alumno' => $alumno
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al conectar con el servicio: ' . $e->getMessage()]);
        }
    }
}
