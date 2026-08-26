<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estatus de Salud</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .center-text { text-align: center; }
        .left-text { text-align: left; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: top; font-size: 11px; }
        .header-table td { border: none; } 
        .signature { margin-top: 50px; text-align: left; }
        .highlight { background-color: #cce5ff; }
    </style>
</head>
<body>

{{-- Encabezado sin bordes --}}
<table width="100%" class="header-table">
    <tr>
        <td width="15%" style="text-align: left;">
            <img src="{{ public_path('images/logo_ipn.png') }}" alt="IPN" style="width:170px;">
        </td>
        <td width="70%" style="text-align: center; font-size:10px; line-height:1.2; font-weight:bold;">
            <strong>INSTITUTO POLITÉCNICO NACIONAL</strong><br>
            <strong>SECRETARÍA DE INNOVACIÓN E INTEGRACIÓN SOCIAL</strong><br>
            <strong>DIRECCIÓN DE EGRESADOS Y SERVICIO SOCIAL</strong><br>
            <strong>DIVISIÓN DE SERVICIO SOCIAL</strong><br>
            <strong>ÁREA DE GESTIÓN DE PROYECTOS DE DESARROLLO SOCIAL</strong><br>
            <strong>PLAN NACIONAL DE SERVICIO SOCIAL EN ZONAS EJIDALES (PLANASSZE) BRIGADAS</strong><br>
            <strong>MULTIDISCIPLINARIAS DE SERVICIO SOCIAL COMUNITARIO</strong>
        </td>
        <td width="15%" style="text-align: right;">
            <img src="{{ public_path('images/logo_dess.png') }}" alt="DESS" style="width:90px;">
        </td>
    </tr>
</table>
<hr>

{{-- Título --}}
<h3 class="center-text">ESTATUS DE SALUD DEL ASPIRANTE A BRIGADA</h3>

{{-- Pregunta 1 --}}
<table border="1" width="100%" style="border-collapse: collapse; font-size:12px;">
    <tr>
        <th colspan="5" class="left-text">1.- ¿Usted padece alguna(s) de la(s) siguientes enfermedades?</th>
    </tr>
    <tr class="highlight">
        <th colspan="2"></th>
        <th>No</th>
        <th>Sí</th>
        <th>Si la respuesta es afirmativa, amplíe su respuesta</th>
    </tr>

    @php
        $enfermedades = [
            'cardiovascular' => 'Cardiovasculares',
            'hipertension' => 'Hipertensión',
            'diabetes' => 'Diabetes',
            'epilepsia' => 'Epilepsia',
            'trastornos_mentales' => 'Trastornos mentales',
            'trastornos_visuales' => 'Trastornos visuales',
            'ets' => 'ETS (incluido VIH/SIDA)',
            'hemorragia_interna' => 'Hemorragia interna',
            'hepatitis' => 'Hepatitis',
            'otra' => 'Otra (especifique)',
        ];
    @endphp

    @foreach($enfermedades as $key => $label)
        <tr>
            <td>{{ strtoupper(substr($key,0,1)) }})</td>
            <td>{{ $label }}</td>
            <td>{{ $brigadista->statusSalud->{$key} == 'No' ? 'X' : '' }}</td>
            <td>{{ $brigadista->statusSalud->{$key} == 'Sí' ? 'X' : '' }}</td>
            <td>{{ $brigadista->statusSalud->{$key.'_detalle'} }}</td>
        </tr>
    @endforeach

    <tr class="highlight">
        <th colspan="2"></th>
        <th style="width:5%">No</th>
        <th style="width:5%">Sí</th>
        <th style="width:60%">Si la respuesta es afirmativa, especifique</th>
    </tr>

    @php
        $preguntas = [
            'tratamiento' => '¿Actualmente está bajo tratamiento médico?¿De qué tipo?',
            'medicamento_actual' => '¿Actualmente usa algún medicamento?¿Cuál?',
            'alergias' => '¿Es usted alérgico(a) a algún medicamento, sustancia o alimento?¿Cuál?',
            'operacion' => '¿Le han practicado alguna operación?',
            'embarazo' => '¿Está usted embarazada?¿Desde cuándo?',
            'grupo_sanguineo' => '¿Cuál es su grupo sanguíneo?',
            'informacion_adicional' => 'Si lo desea, agregue otra información'
        ];
    @endphp

    @foreach($preguntas as $index => $label)
    @php $i = $loop->iteration + 1; @endphp
    <tr>
        <td>{{ $i }}.-</td>
        <td>{{ $label }}</td>
        @if(in_array($index, ['grupo_sanguineo','informacion_adicional']))
            <td></td>
            <td></td>
            <td>{{ $brigadista->statusSalud->{$index.'_detalle'} ?? $brigadista->statusSalud->{$index} }}</td>
        @else
            <td>{{ $brigadista->statusSalud->{$index} == 'No' ? 'X' : '' }}</td>
            <td>{{ $brigadista->statusSalud->{$index} == 'Sí' ? 'X' : '' }}</td>
            <td>{{ $brigadista->statusSalud->{$index.'_detalle'} }}</td>
        @endif
    </tr>
@endforeach
</table>
{{-- Fecha --}}
<p style="text-align: right; margin-top: 30px;">
    México, Ciudad de México a {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
</p>
<div class="signature" style="margin-top: 20px;">
    <p style="margin: 2px 0;">
        <strong>Nombre:</strong> {{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}
    </p>
    <p style="margin: 2px 0;">
        <strong>Firmado con:</strong> 
        @if($brigadista->tipo_firma === 'efirma') e.Firma
        @elseif($brigadista->tipo_firma === 'curp') CURP
        @else No firmado
        @endif
    </p>
    <p style="font-size: 10px; margin: 2px 0; white-space: pre-wrap; word-break: break-word;">
        {{ trim($firma_texto ?? '') }}
    </p>
</div>


</div>


</body>
</html>
