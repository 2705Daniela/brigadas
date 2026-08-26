@extends('layouts.app')

@section('content')
<div class="hero text-center">
    <h1>Brigadas Multidisciplinarias de Servicio Social Comunitario IPN</h1>
    <p>¡PLANASSZE te invita a participar!</p>
</div>

<div class="container mt-5">
    <h2>¿Qué son las Brigadas?</h2>
    <p>
        Grupo de personas (alumnos, pasantes, egresados, titulados, docentes, personal de apoyo y asistencia a la educación investigadores Instituto del Politécnico Nacional), que apoyan a comunidades rurales y suburbanas aportándoles recursos técnicos de servicio y asesorías en la generación de soluciones técnicas problemas prioritarios, abriendo espacios de acción a estudiantes y pasantes en los estados que lo requieran a través de proyectos de desarrollo social y humano como son:
    </p>
    <p>
      •	Atención integral a la salud
    </p>
    <p>
      •	Planes de ordenamiento urbano local
    </p>
    <p>
      •	Proyectos productivos
    </p>
    <p>
      •	Proyectos de desarrollo social (caminos, proyectos de agua, alcantarillado, etc.)
    </p>
    <p>
      •	Planes de desarrollo turistico.
    </p>
    <p>
      •	Rescate de archivos historicos municipales.
    </p>
    <p>Las brigadas se realizan en 3 periodos al año (Primavera, Verano y Otoño)</p>
 @php
    $imagenes = [
        'brigada1.jpeg',
        'brigada2.jpeg',
        'brigada3.jpeg',
        'brigada4.jpeg',
        'brigada5.jpg',
        'brigada6.jpeg',
        'brigada7.jpeg',
        'brigada9.jpeg',
        'brigada10.jpeg'
    ];
@endphp

<div class="container mt-4" style="max-width: 700px;">
    <div id="brigadasCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicadores -->
        <div class="carousel-indicators">
            @foreach($imagenes as $index => $imagen)
                <button type="button" data-bs-target="#brigadasCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($imagenes as $index => $imagen)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ asset('images/' . $imagen) }}" class="d-block w-100 rounded" alt="Brigada {{ $index + 1 }}">
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#brigadasCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#brigadasCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</div>

<!-- Bloque de botones -->
<div class="text-center my-5">
    <a href="{{ route('brigadistas.create') }}" 
       class="btn btn-success px-4 py-2 shadow rounded-pill mx-2"
       style="font-size: 1.1rem;">
        Registrarse
    </a>

    <a href="{{ route('brigadista.login') }}" 
       class="btn btn-primary px-4 py-2 shadow rounded-pill mx-2"
       style="font-size: 1.1rem;">
        Si ya te registraste, ingresa aquí:
    </a>
</div>

@endsection
