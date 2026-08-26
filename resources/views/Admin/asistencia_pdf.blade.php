<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 0px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: middle; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header-table td { border: none; }
        .footer { font-size: 10px; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>

{{-- ENCABEZADO --}}
<table class="header-table">
<tr>
    <td width="15%" style="text-align: left;">
        <img src="{{ public_path('images/logo_ipn.png') }}" alt="IPN" style="width:150px;">
    </td>
    <td width="70%" style="text-align: center; font-size:18px; line-height:1.4; font-weight:bold;">
        <strong>INSTITUTO POLITÉCNICO NACIONAL</strong><br>
        <strong>BRIGADAS MULTIDISCIPLINARIAS DE SERVICIO SOCIAL COMUNITARIO</strong><br>

        @if(!empty($brigada?->brigada) || !empty($brigada?->año))
            <strong>
                {{ $brigada?->brigada }}
                @if(!empty($brigada?->año))
                    ({{ $brigada->año }})
                @endif
            </strong><br>
        @endif

        @if(!empty($brigada?->periodo_inicio) && !empty($brigada?->periodo_fin))
            <strong>
                Del {{ \Carbon\Carbon::parse($brigada->periodo_inicio)->format('d/m/Y') }}
                al {{ \Carbon\Carbon::parse($brigada->periodo_fin)->format('d/m/Y') }}
            </strong><br>
        @endif

        <strong>Lista de Asistencia</strong>
    </td>
    <td width="15%" style="text-align: right;">
        <img src="{{ public_path('images/logo_dess.png') }}" alt="DESS" style="width:90px;">
    </td>
</tr>
</table>
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 10px;
        margin: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    th, td {
        border: 1px solid #000;
        padding: 5px 6px;
        text-align: center;
        vertical-align: middle;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    h2, h3 {
        text-align: center;
        margin: 10px 0 5px;
    }

    .footer {
        font-size: 10px;
        margin-top: 30px;
        text-align: center;
    }

    /* Columnas alineadas */
    .tabla-uniforme {
        table-layout: fixed;
        width: 100%;
    }

    .tabla-uniforme th:nth-child(1),
    .tabla-uniforme td:nth-child(1) { width: 5%; }

    .tabla-uniforme th:nth-child(2),
    .tabla-uniforme td:nth-child(2) { width: 10%; }

    .tabla-uniforme th:nth-child(3),
    .tabla-uniforme td:nth-child(3) { width: 20%; }

    .tabla-uniforme th:nth-child(4),
    .tabla-uniforme td:nth-child(4) { width: 15%; }

    .tabla-uniforme th:nth-child(5),
    .tabla-uniforme td:nth-child(5) { width: 15%; }

    .tabla-uniforme th:nth-child(6),
    .tabla-uniforme td:nth-child(6) { width: 10%; }

    .tabla-uniforme th:nth-child(7),
    .tabla-uniforme td:nth-child(7) { width: 15%; }

    .tabla-uniforme th:nth-child(8),
    .tabla-uniforme td:nth-child(8) { width: 10%; }

    .tabla-uniforme th:nth-child(9),
    .tabla-uniforme td:nth-child(9) { width: 10%; }
</style>

@php

    $supervisoresPorMunicipio = collect();

    foreach ($supervisores as $sup) {

        $asignacion = $sup->asignaciones->first();

        if (!$asignacion) {
            continue;
        }

        $municipio = $asignacion->municipio_asignado;
        $estado = $asignacion->estado_asignado;

        $supervisoresPorMunicipio->push([
            'id_persona' => 'SUP-' . $sup->id,
            'rol' => 'SUPERVISOR',
            'nombre' => "{$sup->nombre} {$sup->apellido_paterno} {$sup->apellido_materno}",
            'telefono' => $sup->telefono,
            'correo' => $sup->correo,
            'boleta' => '-',
            'escuela' => '-',
            'carrera' => '-',
            'estado' => $estado,
            'municipio' => $municipio,
        ]);
    }


    $brigadistasPorProyecto = collect();

    foreach ($brigadistas as $brig) {

        $rol = strtoupper(
            $brig->registros->first()->rol_deseado ?? ''
        );

        if (!in_array($rol, ['COORDINADOR', 'BRIGADISTA'])) {
            continue;
        }

        $asignacion = $brig->asignaciones->first();

        if (!$asignacion) {
            continue;
        }

        $brigadistasPorProyecto->push([
            'id_persona' => 'BRIG-' . $brig->id,
            'rol' => $rol,
            'nombre' => "{$brig->nombre} {$brig->apellido_paterno} {$brig->apellido_materno}",
            'telefono' => $brig->telefono,
            'correo' => $brig->correo,
            'boleta' => $brig->numero_boleta,
            'escuela' => $brig->unidad_academica,
            'carrera' => $brig->programa_academico,
            'estado' => $asignacion->estado_asignado,
            'municipio' => $asignacion->municipio_asignado,
            'proyecto' => $asignacion->proyecto_asignado,
        ]);
    }


    $brigadistasPorProyecto = $brigadistasPorProyecto
        ->unique(function ($persona) {

            return
                $persona['id_persona'] . '|' .
                $persona['municipio'] . '|' .
                $persona['proyecto'];

        })
        ->values();

@endphp


@foreach(
    $supervisoresPorMunicipio
        ->groupBy('estado')
    as $estado => $grupoEstado
)

    @foreach(
        $grupoEstado
            ->groupBy('municipio')
        as $municipio => $grupoMunicipio
    )

        <h2>{{ $municipio }} | {{ $estado }}</h2>
        @if($grupoMunicipio->isNotEmpty())
            <h3>Supervisores</h3>
            <table class="tabla-uniforme">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>BOLETA</th>
                        <th>NOMBRE</th>
                        <th>ESCUELA</th>
                        <th>CARRERA</th>
                        <th>TELÉFONO</th>
                        <th>CORREO</th>
                        <th>ROL</th>
                        <th>FIRMA</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $numSupervisor = 1;
                    @endphp
                    @foreach($grupoMunicipio as $sup)

                        <tr>
                            <td>{{ $numSupervisor++ }}</td>
                            <td>{{ $sup['boleta'] }}</td>
                            <td>{{ $sup['nombre'] }}</td>
                            <td>{{ $sup['escuela'] }}</td>
                            <td>{{ $sup['carrera'] }}</td>
                            <td>{{ $sup['telefono'] }}</td>
                            <td>{{ $sup['correo'] }}</td>
                            <td>{{ $sup['rol'] }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif


        @php

            $proyectosMunicipio = $brigadistasPorProyecto
                ->where('municipio', $municipio)
                ->where('estado', $estado)
                ->groupBy('proyecto');

        @endphp


        @foreach($proyectosMunicipio as $proyecto => $grupoProyecto)

            <h3>{{ $proyecto }}</h3>

            <table class="tabla-uniforme">

                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>BOLETA</th>
                        <th>NOMBRE</th>
                        <th>ESCUELA</th>
                        <th>CARRERA</th>
                        <th>TELÉFONO</th>
                        <th>CORREO</th>
                        <th>ROL</th>
                        <th>FIRMA</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $contador = 1;
                    @endphp

                    {{-- Primero coordinadores --}}
                    @foreach(
                        $grupoProyecto
                            ->where('rol', 'COORDINADOR')
                        as $p
                    )

                        <tr>

                            <td>{{ $contador++ }}</td>
                            <td>{{ $p['boleta'] }}</td>
                            <td>{{ $p['nombre'] }}</td>
                            <td>{{ $p['escuela'] }}</td>
                            <td>{{ $p['carrera'] }}</td>
                            <td>{{ $p['telefono'] }}</td>
                            <td>{{ $p['correo'] }}</td>
                            <td>{{ $p['rol'] }}</td>
                            <td></td>

                        </tr>

                    @endforeach


                    {{-- Después brigadistas --}}
                    @foreach(
                        $grupoProyecto
                            ->where('rol', 'BRIGADISTA')
                        as $p
                    )

                        <tr>

                            <td>{{ $contador++ }}</td>

                            <td>{{ $p['boleta'] }}</td>

                            <td>{{ $p['nombre'] }}</td>

                            <td>{{ $p['escuela'] }}</td>

                            <td>{{ $p['carrera'] }}</td>

                            <td>{{ $p['telefono'] }}</td>

                            <td>{{ $p['correo'] }}</td>

                            <td>{{ $p['rol'] }}</td>

                            <td></td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @endforeach

    @endforeach

@endforeach

<div class="footer">
    <p>PLANASSZE - Dirección de Egresados y Servicio Social | Instituto Politécnico Nacional</p>
</div>


</body>
</html>
