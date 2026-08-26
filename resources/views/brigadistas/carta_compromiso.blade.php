<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carta Compromiso Brigadista</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 30px; }
        .title { font-size: 10px; font-weight: bold; text-align: center; line-height: 1.2; }
        .section-title { font-weight: bold; background: #eee; padding: 5px; margin-top: 15px; }
        .page-break { page-break-after: always; }
        .content { text-align: justify; margin-top: 20px; }
        .compromiso { margin-top: 20px; }
        .signature { margin-top: 50px; }
    </style>
</head>
<body>


{{-- Página 1 --}}
<table width="100%">
    <tr>
        <td width="15%" style="text-align:left;">
            <img src="{{ public_path('images/logo_ipn.png') }}" alt="IPN" style="width:170px;">
        </td>
        <td width="70%" class="title">
            INSTITUTO POLITÉCNICO NACIONAL<br>
            SECRETARÍA DE INNOVACIÓN E INTEGRACIÓN SOCIAL<br>
            DIRECCIÓN DE EGRESADOS Y SERVICIO SOCIAL<br>
            DIVISIÓN DE SERVICIO SOCIAL<br>
            ÁREA DE GESTIÓN DE PROYECTOS DE DESARROLLO SOCIAL
        </td>
        <td width="15%" style="text-align:right;">
            <img src="{{ public_path('images/logo_dess.png') }}" alt="DESS" style="width:90px;">
        </td>
    </tr>
</table>
<hr>

<br><h3 style="text-align:center;">CARTA COMPROMISO BRIGADISTA</h3><br>
<p style="text-align: right;">Ciudad de México a {{ $fecha }}</p>
<p>LIC. BEATRIZ RANGEL ROMO<br>
DIRECTORA DE EGRESADOS Y SERVICIO SOCIAL<br>
PRESENTE</p>

<div class="content">
<p>
    El que suscribe 
    <strong>{{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</strong>, 
    identificado con número de boleta 
    <strong>{{ $brigadista->numero_boleta }}</strong> 
    de la escuela 
    <strong>{{ $brigadista->unidad_academica }}</strong> 
    y carrera 
    <strong>{{ $brigadista->programa_academico }}</strong>, 
    manifiesto mi interés y compromiso para participar en las Brigadas Multidisciplinarias de Servicio Social Comunitario. 
    Para tal efecto, me encuentro dispuesto a realizar los trámites y suministrar toda la documentación requerida por ustedes cuando me sea requerida, así como cumplir con los siguientes deberes y obligaciones:
</p>

I. Ser alumno del Instituto Politécnico Nacional.<br>
II. Realizar el preregistro para asistir a las Brigadas de Servicio Social Comunitario, en las fechas indicadas y entregar la documentación requerida.<br>
III. Para efecto de considerar servicio social en la brigada deberá previamente realizar el registro en el Sistema Institucional de Servicio Social (SISS) indicando el Programa “Plan Nacional de Servicio Social en Zonas Ejidales (PLANASSZE)”.<br>
IV. Cumplir en todos los términos con el programa de trabajo establecido por la Dirección de Egresados y Servicio Social (DESS).<br>
V. Ser puntual en el horario de actividades.<br>
VI. Asistir a las juntas y reuniones de integración, capacitación, trabajo y entrega de resultados que sean convocadas por autoridades de la DESS y por los coordinadores de la brigada.<br>
VII. Presentar el test de aptitudes y acudir a la entrevista en el horario y días señalados por la DESS.<br>
VIII. Entregar el certificado médico, la vigencia de servicios médicos, completar el cuestionario de salud.<br>
IX. Presentarme a la ceremonia de salida con la vestimenta indicada y/o proporcionada por la DESS.<br>
X. Realizar el pase de lista al momento de abordar la unidad de transporte, así como de manera diaria con el coordinador responsable.<br>
XI. Portar en todo momento el gafete que me identifique como parte de la brigada, así como la credencial que me acredite como parte de la comunidad del IPN, además del vestuario proporcionado por la DESS.<br>
XII. Hacer conciencia del valor de la imagen del IPN ante los pobladores de las localidades visitadas y conducirme conforme a los principios de esta casa de estudios.<br>
XIII. Evitar ausentarme de las actividades encomendadas sin el permiso correspondiente.<br>
XIV. Posterior a la jornada de trabajo, permanecer en el alojamiento hasta el inicio de la siguiente jornada.<br>
XV. En caso de emergencia, notificar de inmediato al coordinador responsable.<br>
XVI. Notificar síntomas de enfermedad e seguir protocolo correspondiente.<br>
XVII. Comunicar irregularidades observadas.<br>
XVIII. Guardar respeto a superiores, autoridades, población y compañeros brigadistas.
XIX. Respetar a los integrantes de la comunidad a la que fui asignado, así como sus costumbres y tradiciones.<br>
XX. Abstenerme de ingerir bebidas alcohólicas, drogas o estupefacientes, pues de ser el caso, seré suspendido inmediatamente de la brigada y del servicio social.<br>
XXI. No portar durante el período de brigada armas, instrumentos o explosivos que pueden poner en peligro mi integridad física.<br>
</p>
</div>

<div class="page-break"></div>

{{-- Página 2 --}}
<div class="content">
<p>
XXII. Responder del manejo de documentos, equipos de ingeniería y del área de la salud, objetos, valores y efectos que se me confieran.<br>
XXIII. El manejo de documentos, objetos, equipos y valores que se me confieran no podrán ser utilizados para fines distintos a los de la brigada.<br>
XXIV. Hacerme responsable de mi equipaje y objetos personales; el IPN y la DESS no se hacen responsables de equipos u objetos perdidos o dañados.<br>
XXV. Abstenerme de realizar actividades distintas de las asignadas.<br>
XXVI. No realizaré cobro alguno por los servicios otorgados a las comunidades.<br>
XXVII. Evitar comprometer con mi imprudencia o negligencia mi seguridad o la de los demás.<br>
XXVIII. Abstenerme de sustraer, alterar o hacer uso ilícito de documentos oficiales.<br>
XXIX. Entregar en tiempo y forma los resultados de mi participación a la DESS y al coordinador.<br>
XXX. Dar conclusión a los proyectos y trabajos desarrollados durante la brigada.<br>
XXXI. Al término de la brigada llegaré a las instalaciones del IPN; no solicitaré bajar en lugar distinto.<br>
XXXII. Por disposición oficial, queda prohibido ingresar a ríos, lagos, océanos, albercas o participar en eventos políticos y sociales durante la brigada.<br><br>

Por las faltas que se presenten durante el desarrollo de la brigada y sus actividades complementarias, previo análisis por parte de las autoridades de la Dirección de Egresados y Servicio Social (DESS), podrá solicitar la aplicación, según el caso y de conformidad con las políticas, criterios y lineamientos correspondientes las siguientes sanciones con fundamento en los artículos 48° del Reglamento de Servicio Social y 110° del Reglamento Interno, ambos del Instituto Politécnico Nacional.<br><br>

La aplicación de estas sanciones se hará en estricto apego al principio de proporcionalidad entre la gravedad de la falta y la sanción.<br><br>

La Dirección de Egresados y Servicio Social, se reservan el derecho de admisión, para participar en las brigadas Multidisciplinarias de Servicio Social Comunitario. Todas las fracciones anteriores son de observancia general y obligatoria durante el período y actividades de brigada.
</p>

{{-- Firma --}}
<div class="signature" style="margin-top: 20px;">
    <p style="margin: 2px 0;">
        <strong>Nombre:</strong> {{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}
    </p>
    <p style="margin: 2px 0;">
        <strong>No. Boleta/Empleado:</strong> {{ $brigadista->numero_boleta }}
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
    <p style="margin: 2px 0;">
        <em>Bajo protesta de decir verdad</em>
    </p>
</div>




</body>
</html>

