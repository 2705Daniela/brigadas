<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>
    @page {
        margin: 0;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: Helvetica, Arial, sans-serif;
    }

    .pagina {
        width: 100%;
        height: 100vh;
        page-break-after: always;
        text-align: center;
    }

    .pagina:last-child {
        page-break-after: auto;
    }

    .hoja {
        width: 1.5cm;
        border-spacing: 0;
        border-collapse: collapse;
        margin: 0 auto;
    }

    .credencial {
        width: 9cm;
        height: 13cm;
        position: relative;
        overflow: hidden;
        border: 0.5pt solid #333;
        vertical-align: top;
        padding: 0;
        border-radius: 10px;
        background-image: url('{{ public_path("images/guinda.png") }}');
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }

    .header-imagen {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5cm;
        z-index: 1;
    }

    .banner-img {
        width: 100%;
        height: 100%;
    }

    .header-frente {
        position: absolute;
        top: .7cm;
        width: 100%;
        text-align: center;
        z-index: 5;
    }

    .titulo-img {
        width: 120%;
        max-width: 7.8cm;
    }

    .foto-container {
        position: absolute;
        top: 1.8cm;
        left: 50%;
        transform: translateX(-50%);
        width: 7.8cm;
        height: 7.8cm;
        z-index: 10;
    }

    .foto {
        position: absolute;
        width: 4.2cm;
        height: 4.2cm;
        top: 1.8cm;
        left: 1.8cm;
        object-fit: cover;
        border-radius: 50%;
    }

    .marco {
        position: absolute;
        width: 7.8cm;
        height: 7.8cm;
        top: 0;
        left: 0;
    }

    .info-personal {
        position: absolute;
        top: 8.8cm;
        width: 100%;
        text-align: center;
        color: white;
    }

    .puesto {
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }

    .nombre {
        font-size: 17px;
        font-weight: bold;
        line-height: 1.2;
        padding: 0 15px;
        text-transform: uppercase;
    }

    .boleta {
        font-size: 13px;
        margin-top: 8px;
        opacity: .9;
    }

    .footer-logos {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 1.5cm;
        background-color: white;
        text-align: center;
        border-top: 4px solid #4A1020;
    }

    .logo-ipn-frente {
        height: 1.1cm;
        margin-top: .25cm;
    }

    .fondo-reverso {
        background-image: url('{{ public_path("images/atras.jpg") }}');
        background-size: 100% 100%;
        background-repeat: no-repeat;
        background-color: white;
    }

    .contenido-reverso {
        width: 100%;
        text-align: center;
        padding-top: 1cm;
    }

    .emergencias {
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 20px;
        color: #4A1020;
    }

    .directivos {
        font-size: 15px;
        line-height: 1.2;
        color: black;
    }

    .nombre-firmante {
        font-weight: bold;
        display: block;
        margin-top: 8px;
        color: black;
    }

    .cargo {
        font-weight: normal;
        display: block;
        font-size: 12px;
        color: black;
    }

    .footer-reverso-logos {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 1cm;
        text-align: center;
        margin-top: .4cm;
    }

    .iconos-lateral {
        position: absolute;
        left: -1cm;
        top: 7.5cm;
        width: 4cm;
        height: auto;
    }
</style>

</head>

<body>

@foreach($brigadistas as $brigadista)

<div class="pagina">

    <table class="hoja">
        <tr>

            <td class="credencial">

                <div class="header-imagen">
                    <img src="{{ public_path('images/estaciones.png') }}"
                         class="banner-img">
                </div>

                <div class="header-frente">
                    <img src="{{ public_path('images/titi.png') }}"
                         class="titulo-img">
                </div>

                <div class="foto-container">

                    @if($brigadista->foto)
                        <img src="{{ public_path('storage/' . $brigadista->foto) }}"
                             class="foto">
                    @endif

                    <img src="{{ public_path('images/marco.png') }}"
                         class="marco">

                </div>

                <div class="info-personal">

                    <div class="puesto">
                        BRIGADISTA
                    </div>

                    <div class="nombre">
                        {{ $brigadista->nombre }}<br>
                        {{ $brigadista->apellido_paterno }}
                        {{ $brigadista->apellido_materno }}
                    </div>

                    <div class="boleta">
                        BOLETA: {{ $brigadista->numero_boleta }}
                    </div>

                </div>

                <img src="{{ public_path('images/iconss.png') }}"
                     class="iconos-lateral">

                <div class="footer-logos">
                    <img src="{{ public_path('images/ipn.png') }}"
                         class="logo-ipn-frente">
                </div>

            </td>


            <td class="credencial fondo-reverso">

                <div class="contenido-reverso">

                    <div class="emergencias">
                        PARA EMERGENCIAS <br>
                        COMUNICARSE A LOS<br>
                        TELS.: (55) 5729 6000<br>
                        EXT. 51624 / 51673 / 51679
                    </div>

                    <div class="directivos">

                        <span class="nombre-firmante">
                            Dr. Arturo Reyes Sandoval
                        </span>

                        <span class="cargo">
                            Director General del <br>
                            Instituto Politécnico Nacional
                        </span>


                        <span class="nombre-firmante">
                            M.C.E. Yessica Gasca Castillo
                        </span>

                        <span class="cargo">
                            Secretaria de Innovación <br>
                            e Integración Social
                        </span>


                        <span class="nombre-firmante">
                            Lic. Beatriz Rangel Romo
                        </span>

                        <span class="cargo">
                            Directora de Egresados y Servicio Social
                        </span>


                        <span class="nombre-firmante">
                            M. en C. Luis Alberto Méndez Cruz
                        </span>

                        <span class="cargo">
                            Jefe de la División de Servicio Social
                        </span>

                    </div>

                    <div style="
                        font-weight: bold;
                        margin-top: 15px;
                        font-size: 15px;
                    ">
                        No. folio: {{ $brigadista->id }}
                    </div>

                </div>

                <div class="footer-reverso-logos">

                    <img src="{{ public_path('images/logo_siss.png') }}"
                         style="height: .9cm; margin-right: .5cm;">

                    <img src="{{ public_path('images/logo_dess.png') }}"
                         style="height: .9cm;">

                </div>

            </td>

        </tr>
    </table>

</div>

@endforeach

</body>
</html>