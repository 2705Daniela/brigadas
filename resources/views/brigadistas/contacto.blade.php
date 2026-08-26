@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 900px;">
    {{-- Título centrado --}}
    <h2 class="text-center mb-1 fw-bold">DIRECTORIO</h2>
    <h4 class="text-center mb-4" style="color:#6A1B4D;">PLANASSZE</h4>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Extensión</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Lic. Nallely Azucena Zarco Delgado</strong><br>
                        <small>Apoyo al Área de Gestión de Proyectos de Desarrollo Social</small>
                    </td>
                    <td><i class="bi bi-envelope-fill"></i> nzarco@ipn.mx</td>
                    <td><i class="bi bi-telephone-fill"></i> 51662</td>
                </tr>
                <tr>
                    <td>Vianney Adriana Lopez Carrillo</td>
                    <td><i class="bi bi-envelope-fill"></i> valopezc@ipn.mx</td>
                    <td><i class="bi bi-telephone-fill"></i> 51679</td>
                </tr>
                <tr>
                    <td>Alberto Alejandro Lopez Luna</td>
                    <td><i class="bi bi-envelope-fill"></i> planassze@ipn.mx</td>
                    <td><i class="bi bi-telephone-fill"></i> 51624</td>
                </tr>
                <tr>
                    <td>Miguel Angel Heroz Enriquez</td>
                    <td><i class="bi bi-envelope-fill"></i> mheraze1500@alumno.ipn.mx</td>
                    <td><i class="bi bi-telephone-fill"></i> 51619</td>
                </tr>
                <tr>
                    <td>Reyna Martinez Perez</td>
                    <td><i class="bi bi-envelope-fill"></i> rmartinezpe@ipn.mx</td>
                    <td><i class="bi bi-telephone-fill"></i> 51673</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-end">
        <a href="{{ route('brigadistas.landing') }}" class="btn btn-secondary btn-lg">Regresar</a>
    </div>
</div>
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
@endsection

