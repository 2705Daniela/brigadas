<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Brigadista</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .center-text { text-align: center; }
        .section { margin-top: 20px; }
        .section-title { font-weight: bold; background-color: #eee; padding: 5px; }
        .left, .right { width: 48%; display: inline-block; vertical-align: top; }
        .field { margin-bottom: 5px; }
        .photo { border: 1px solid #000; width: 120px; height: 150px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px; vertical-align: top; }
    </style>
</head>
<body>
  

  <table width="100%" style="border-collapse: collapse; text-align: center;">
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


<table width="100%" style="border-collapse: collapse; margin-top:10px;">
    <tr>
        <td width="65%" style="font-size:12px; line-height:1.1;">
            <div>FECHA DE RECEPCIÓN:</strong> {{ $brigadista->fecha_recepcion }}</div>
            <div>UNIDAD RESPONSABLE:</strong> Dirección de Egresados y Servicio Social</div>
            <div>CARÁCTER:</strong> Confidencial</div>
            <div>PARTES CONFIDENCIALES:</strong> Todo el documento</div>
            <div>FUNDAMENTO LEGAL:</strong> artículo 3, fracción II y 21, LFTAIPG, lineamiento 32, fracción VII, VIII, XV</div>
        </td>

        <td width="35%" style="text-align:right; vertical-align:top;">
            @if($brigadista->foto)
                <img src="{{ public_path('storage/' . $brigadista->foto) }}" alt="Foto" width="120" height="150" style="object-fit: cover;">
            @endif
        </td>
    </tr>
</table>
<!-- Encabezado con folio y boleta -->
<table width="100%" style="border-collapse: collapse; font-size:12px; line-height:1.2; margin-top:10px; padding:5px;">
    <tr>
        <td>No. Folio: {{ $brigadista->id }}</td>
        <td>Fecha: {{ $brigadista->created_at->format('d/m/Y') }}</td>
        <td></td>
    </tr>
    <tr>
        <td>No. Boleta: {{ $brigadista->numero_boleta }}</td>
        <td></td>
        <td></td>
    </tr>
</table>

<!-- Datos del Aspirante -->
<table width="100%" style="border-collapse: collapse; font-size:12px; line-height:1.2; margin-top:10px; padding:5px;">
    <!-- Encabezado en guinda -->
    <tr>
        <td colspan="3" style="text-align:center; font-weight:bold; padding:5px; background-color:#6A1B4D; color:white;">
            DATOS DEL ASPIRANTE
        </td>
    </tr>
    <!-- Nombre y CURP/RFC -->
    <tr>
        <td>Nombre: {{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</td>
        <td colspan="2">CURP/RFC: {{ $brigadista->curp }}</td>
    </tr>
    <!-- No. Seguro Social, Género, Nacionalidad -->
    <tr>
        <td>No. de Seguro Social: {{ $brigadista->nss }}</td>
        <td>Género: {{ $brigadista->genero }}</td>
    </tr>
    <!-- Domicilio particular -->
    <tr>
        <td colspan="3">Domicilio particular:</td>
    </tr>
    <tr>
        <td>Calle: {{ $brigadista->calle }}</td>
        <td>No. Int: {{ $brigadista->no_int }}</td>
        <td>No. Ext: {{ $brigadista->no_ext }}</td>
    </tr>
    <tr>
        <td>Colonia: {{ $brigadista->colonia }}</td>
        <td>Municipio: {{ $brigadista->municipio }}</td>
        <td>CP: {{ $brigadista->cp }}</td>
    </tr>
    <tr>
        <td>Estado: {{ $brigadista->estado }}</td>
        <td>Talla de playera: {{ $brigadista->talla }}</td>
        <td>Unidad académica: {{ $brigadista->unidad_academica }}</td>
    </tr>
    <tr>
        <td>Correo electrónico: {{ $brigadista->correo }}</td>
        <td>Teléfono celular: {{ $brigadista->telefono }}</td>
        <td>Carrera: {{ $brigadista->programa_academico }}</td>
    </tr>
    <tr>
        <td>Tu participación es: {{ $brigadista->modalidad }}</td>
        <td>Especialidad: {{ $brigadista->especialidad }}</td>
        <td></td>
    </tr>
    <tr>
        <td>Roll de participación: {{ $brigadista->rol_deseado ?? '' }}</td>
        <td>Situación academica: {{ $brigadista->situacion_academica ?? '' }}</td>

        <td>Edad: {{ $brigadista->edad }}</td>
    </tr>
</table>

<!-- Participación Brigadas -->
<table width="100%" style="border-collapse: collapse; font-size:12px; line-height:1.2; margin-top:10px; padding:5px;">
    <!-- Encabezado en guinda y en negritas -->
    <tr>
        <td colspan="4" style="text-align:center; font-weight:bold; padding:5px; background-color:#6A1B4D; color:white;">
            PARTICIPACIÓN BRIGADAS
        </td>
    </tr>
    <!-- Programa de servicio social -->
    <tr>
        <td colspan="4">En caso de estar realizando Servicio Social por medio de las brigadas (PLANASSZE),<br>
            ¿Se encuentra registrado en el SISS? {{ $brigadista->siss }}</td>
    </tr>
    <!-- Participación anterior -->
    <tr>
        <td>¿Has participado anteriormente?¿En cuántas? {{ $brigadista->participacion_anterior }}</td>
        <td>Estado/Municipio: {{ $brigadista->municipio_anterior }}</td>
    </tr>
</table>

<!-- Emergencia / Contacto -->
<table width="100%" style="border-collapse: collapse; font-size:12px; margin-top:10px;">
    <!-- Encabezado en guinda y negritas -->
    <tr>
        <td colspan="3" style="text-align:center; font-weight:bold; padding:5px; background-color:#6A1B4D; color:white;">
            EN CASO DE EMERGENCIA AVISAR A
        </td>
    </tr>

    <!-- Nombre y Parentesco -->
    <tr>
        <td>Nombre: {{ $brigadista->contacto_nombre }}</td>
        <td>Parentesco: {{ $brigadista->contacto_parentesco }}</td>
        <td></td>
    </tr>

    <!-- Domicilio particular -->
    <tr>
        <td colspan="3">Domicilio particular:</td>
    </tr>
    <tr>
        <td>Calle: {{ $brigadista->calle_contacto }}</td>
        <td>No. Int: {{ $brigadista->no_int_contacto }}</td>
        <td>No. Ext: {{ $brigadista->no_ext_contacto }}</td>
    </tr>
    <tr>
        <td>Colonia: {{ $brigadista->colonia_contacto }}</td>
        <td>Municipio: {{ $brigadista->municipio_contacto }}</td>
        <td>CP: {{ $brigadista->cp_contacto }}</td>
    </tr>

    <!-- Estado y Teléfono -->
    <tr>
        <td>Estado: {{ $brigadista->estado_contacto }}</td>
        <td colspan="2">Teléfono: {{ $brigadista->telefono_contacto }}</td>
    </tr>
</table>

<p style="font-size:8px; line-height:1.2; margin-top:10px; text-align:justify;">
    Manifiesto que al llenar esta solicitud queda formalmente establecido mi compromiso para acatar las disposiciones de las autoridades de la D.E.S.S. al asignarme a alguna brigada reconociendo a su vez que estoy enterado(a) del contenido de los artículos 8, 23 y 28 del Reglamento de Servicio Social I.P.N. Asimismo me comprometo a conocer detalladamente la Guía del Coordinador de Brigada y asistir a las reuniones de trabajo que sean convocadas por esta Dirección antes y después de la brigada.
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


< <div style="position: absolute; bottom: 10px; left: 0; right: 0; padding: 10px 20px; font-size:8px; line-height:1.2; text-align:justify;">
        DECLARACIÓN DEL PRESTADOR ENTREGO LA INFORMACIÓN DESCRITA EN EL PRESENTE FORMATO CONSCIENTE DE QUE LA MAYOR PARTE DE LOS DATOS ES INFORMACIÓN CLASIFICADA COMO CONFIDENCIAL EN TERMINES DEL ARTICULO 18 DE LA LFTAIPG Y DE CONFORMIDAD CON LOS ARTÍCULOS 19, 21 Y 22 FRACCIÓN V DE LA LFTAIPG, OTORGO MI CONSENTIMIENTO PARA QUE SEA UTILIZADA EN LOS TRAMITES INHERENTES A LAS BRIGADAS MULTIDISCIPLINARIAS DE SERVICIO SOCIAL COMUNITARIO, SIN QUE PUEDAN SER USADOS PARA PROPÓSITOS DISTINTOS A AQUELLOS PARA LOS QUE DOY MI CONSENTIMIENTO.
    </div>
</div>



</body>
</html>
