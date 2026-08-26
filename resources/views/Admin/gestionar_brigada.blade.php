@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h2>Gestionar Brigadas</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('admin.crear_brigada') }}" class="btn btn-primary mb-3">
        Crear nueva brigada
    </a>

    <table class="table table-bordered table-hover">

        <thead class="table-dark">
            <tr>
                <th>Número de Brigada</th>
                <th>Nombre</th>
                <th>Año</th>
                <th>Periodo</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>

        <tbody>

            @forelse($brigadas as $brigada)

                <tr>

                    <td>{{ $brigada->id }}</td>

                    <td>{{ $brigada->brigada }}</td>

                    <td>{{ $brigada->año }}</td>

                    <td>
                        {{ $brigada->periodo_inicio }}
                        -
                        {{ $brigada->periodo_fin }}
                    </td>

                    <td>
                        @if($brigada->estado)
                            <span class="badge bg-success">
                                Activa
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                Inactiva
                            </span>
                        @endif
                    </td>

                    <td>

                        <a
                            href="{{ route('admin.editar_brigada', $brigada->id) }}"
                            class="btn btn-sm btn-warning">
                            Editar
                        </a>

                        <form
                            action="{{ route('admin.eliminar_brigada', $brigada->id) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('¿Estás seguro de eliminar esta brigada?');">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-sm btn-danger">
                                Eliminar
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center">
                        No hay brigadas registradas.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>
@endsection