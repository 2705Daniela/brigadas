<!DOCTYPE html>
<html>
<head>
    <title>Brigadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
    html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    box-sizing: border-box;
}
</style>
</head>

<body>
<div class="d-flex justify-content-between align-items-center px-4" style="background-color: #6A1B4D; color: white; height: 50px; font-size: 16px;">
    <div>
        <img src="/images/gobierno-mx.png" alt="Gobierno de México" style="height: 60px;">
    </div>
    <div class="d-flex align-items-center">
        <a href="https://www.gob.mx/" class="text-white text-decoration-none mx-2">Trámites</a>
        <a href="https://www.gob.mx/gobierno" class="text-white text-decoration-none mx-2">Gobierno</a>
        <a href="#" class="text-white text-decoration-none mx-2">
            <i class="bi bi-search"></i>
        </a>
    </div>
</div>

<nav class="navbar navbar-expand-lg" style="background-color: white; border-bottom: 1px solid #ddd;">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="/images/sep.png" alt="SEP" style="height: 70px; margin-right: 20px;">
            <img src="/images/ipn.png" alt="IPN" style="height: 80px;">
        </div>

        <div class="d-flex align-items-center ms-auto" id="navbarLinks">
            <a href="https://www.ipn.mx/directorio-telefonico.html" class="text-dark text-decoration-none mx-1" style="font-size: 12px;">DIRECTORIO |</a>
            <a href="https://www.ipn.mx/correo-electronico.html" class="text-dark text-decoration-none mx-1" style="font-size: 12px;">CORREO |</a>
            <a href="https://www.ipn.mx/calendario-academico.html" class="text-dark text-decoration-none mx-1" style="font-size: 12px;">CALENDARIO |</a>
            <a href="https://www.ipn.mx/transparencia/" class="text-dark text-decoration-none mx-1" style="font-size: 12px;">TRANSPARENCIA |</a>
            <a href="https://www.ipn.mx/proteccion-datos-personales/" class="text-dark text-decoration-none mx-1" style="font-size: 12px;">PROTECCIÓN DE DATOS</a>
        </div>
    </div>
</nav>

<div class="d-flex justify-content-center" style="background-color: #000000; height: 40px; align-items: center;">
    <a href="https://www.ipn.mx/" class="text-white text-decoration-none mx-3" style="font-size: 14px;">INICIO</a>
    <a href="https://www.ipn.mx/dess/" class="text-white text-decoration-none mx-3" style="font-size: 14px;">DESS </a>
    <a href="{{ route('brigadistas.contacto') }}" class="text-white text-decoration-none mx-3" style="font-size: 14px;">CONTACTO</a>
    <a href="{{ route('brigadistas.landing') }}" class="text-white text-decoration-none mx-3" style="font-size: 14px;">PLANASSZE</a>
     <a href="{{ route('brigadistas.landing') }}" class="text-white text-decoration-none mx-3" style="font-size: 14px;">INICIO BRIGADAS</a>

</div>

<div class="mt-4">
    @yield('content')
</div>


<div style="background-color:#000; color:white; width:100%; display:flex; flex-wrap:wrap; padding:20px;">
    <div style="flex:0 0 auto; margin-right:20px;">
        <img src="/images/sep.png" alt="SEP" style="height:60px;">
    </div>
    <div style="flex:1 1 0; font-size:12px; line-height:1.5;">
        <strong>INSTITUTO POLITÉCNICO NACIONAL</strong><br>
        D.R. Instituto Politécnico Nacional (IPN). Av. Luis Enrique Erro S/N, Unidad Profesional Adolfo López Mateos, Zacatenco, Alcaldía Gustavo A. Madero, C.P. 07738, Ciudad de México. Conmutador: 55 57 29 60 00 / 55 57 29 63 00.<br>
        Esta página es una obra intelectual protegida por la Ley Federal del Derecho de Autor, puede ser reproducida con fines no lucrativos, siempre y cuando no se mutile, se cite la fuente completa y su dirección electrónica; su uso para otros fines, requiere autorización previa y por escrito de la Dirección General del Instituto.
    </div>
</div>


<div style="background-color:#6A1B4D; color:white; width:100%; display:flex; flex-wrap:wrap; padding:20px;">
    <div style="flex:0 0 auto; margin-right:20px;">
        <img src="/images/gobierno-mx.png" alt="Gobierno de México" style="height:60px;">
    </div>
    <div style="flex:1 1 0; display:flex; flex-wrap:wrap; gap:40px;">
        <div>
            <h6 class="text-uppercase">Enlaces</h6>
            <ul style="list-style:none; padding:0; margin:0;">
                <li><a href="https://datos.gob.mx/" style="color:white; text-decoration:none;">Datos</a></li>
                <li><a href="https://consultapublicamx.plataformadetransparencia.org.mx/vut-web/faces/view/consultaPublica.xhtml?idEntidad=MzM=&idSujetoObligado=MTAwMDE=#inicio" style="color:white; text-decoration:none;">Portal de Obligaciones de Transparencia</a></li>
                <li><a href="https://transparencia.gob.mx/home.html" style="color:white; text-decoration:none;">Transparencia para el Pueblo</a></li>
                <li><a href="https://alertadores.funcionpublica.gob.mx/" style="color:white; text-decoration:none;">Alerta</a></li>
                <li><a href="https://sidec.funcionpublica.gob.mx/" style="color:white; text-decoration:none;">Denuncia</a></li>
            </ul>
        </div>
        <div>
            <h6 class="text-uppercase">Datos</h6>
            <ul style="list-style:none; padding:0; margin:0;">
                <li>¿Qué es gob.mx?</li>
                <li>Es el portal único de trámites, información y participación ciudadana.</li>
                <li><a href="https://www.gob.mx/que-es-gobmx" style="color:white; text-decoration:none;">Leer más</a></li>
                <li><a href="https://datos.gob.mx/" style="color:white; text-decoration:none;">Portal de datos abiertos</a></li>
                <li><a href="https://www.gob.mx/accesibilidad" style="color:white; text-decoration:none;">Declaración de accesibilidad</a></li>
                <li><a href="https://www.gob.mx/terminos" style="color:white; text-decoration:none;">Términos y Condiciones</a></li>
            </ul>
        </div>
        <div>
            <h6 class="text-uppercase">Síguenos en</h6>
            <ul style="list-style:none; padding:0; margin:0; display:flex; gap:10px;">
                <li><a href="https://www.facebook.com/gobmexico" style="color:white; text-decoration:none;"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="https://x.com/GobiernoMX" style="color:white; text-decoration:none;"><i class="fab fa-twitter"></i></a></li>
                <li><a href="https://www.instagram.com/gobmexico/" style="color:white; text-decoration:none;"><i class="fab fa-instagram"></i></a></li>
                <li><a href="https://www.youtube.com/@gobiernodemexico" style="color:white; text-decoration:none;"><i class="fab fa-youtube"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<div style="background-color: rgba(0,0,0,0.2); width:100%; text-align:center; font-size:12px; padding:10px 0;">
    © {{ date('Y') }} Instituto Politécnico Nacional | Todos los derechos reservados
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
