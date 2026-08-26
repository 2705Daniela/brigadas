@extends('layouts.app')

@section('content')

<div class="container">

    <div class="text-center mb-4">
        <h4 style="color:#6A1B4D; font-weight:bold;">CREDENCIAL DIGITAL</h4>

        <a href="{{ route('brigadistas.credencial.pdf', $brigadista->id) }}"
           class="btn btn-danger mt-3"
           target="_blank">
            Descargar en PDF
        </a>
    </div>

    <div class="d-flex justify-content-center">

        <div class="credencial-wrapper">

            {{-- FRENTE --}}
            <div class="credencial">

                <div class="header-imagen">
                    <img src="{{ asset('images/estaciones.png') }}" class="banner-img">
                </div>

                <div class="header-frente">
                    <img src="{{ asset('images/titi.png') }}" class="titulo-img">
                </div>

                <div class="foto-container">
                    <img src="{{ asset('storage/'.$brigadista->foto) }}" class="foto">
                    <img src="{{ asset('images/marco.png') }}" class="marco">
                </div>

                <div class="info-personal">
                    <div class="puesto">BRIGADISTA</div>
                    <div class="nombre">
                        {{ $brigadista->nombre }}<br>
                        {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}
                    </div>
                    <div class="boleta">
                        BOLETA: {{ $brigadista->numero_boleta }}
                    </div>
                </div>

                <img src="{{ asset('images/iconss.png') }}" class="iconos-lateral">

                <div class="footer-logos">
                    <img src="{{ asset('images/ipn.png') }}" class="logo-ipn-frente">
                </div>

            </div>

        </div>

    </div>

</div>

<style>

.credencial-wrapper{
    display:flex;
    gap:20px;
}

.credencial{
    width:9cm;
    height:13cm;
    position:relative;
    overflow:hidden;
    border-radius:10px;
    border:1px solid #333;
    background-image:url('{{ asset("images/guinda.png") }}');
    background-size:100% 100%;
}


.header-imagen{
    position:absolute;
    top:0;
    width:100%;
    height:5cm;
}

.banner-img{
    width:100%;
    height:100%;
}

.header-frente{
    position:absolute;
    top:.7cm;
    width:100%;
    text-align:center;
}

.titulo-img{
    max-width:7.8cm;
}

.foto-container{
    position:absolute;
    top:1.8cm;
    left:50%;
    transform:translateX(-50%);
    width:7.8cm;
    height:7.8cm;
}

.foto{
    position:absolute;
    width:4.2cm;
    height:4.2cm;
    top:1.8cm;
    left:1.8cm;
    object-fit:cover;
    border-radius:50%;
}

.marco{
    position:absolute;
    width:7.8cm;
    height:7.8cm;
}

.info-personal{
    position:absolute;
    top:8.8cm;
    width:100%;
    text-align:center;
    color:white;
}

.puesto{
    font-size:16px;
    font-weight:bold;
}

.nombre{
    font-size:17px;
    font-weight:bold;
}

.boleta{
    font-size:13px;
}

.footer-logos{
    position:absolute;
    bottom:0;
    width:100%;
    height:1.5cm;
    background:white;
    text-align:center;
}

.logo-ipn-frente{
    height:1.1cm;
    margin-top:.25cm;
}

.iconos-lateral{
    position:absolute;
    left:-1cm;
    top:7.5cm;
    width:4cm;
}

</style>

@endsection