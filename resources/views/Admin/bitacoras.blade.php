@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-center fw-bold mb-4" style="color:#6A1B4D;">BITACORA</h2>

    {{-- Tabla de bitácoras --}}
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Brigadista</th>
                <th>Acción</th>
                <th>Observaciones</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bitacoras as $bitacora)
                <tr>
                    <td>{{ $bitacora->id }}</td>
                    <td>{{ $bitacora->usuario->name ?? 'Sistema' }}</td>
                    <td>
                        @if($bitacora->brigadista)
                            {{ $bitacora->brigadista->nombre }} {{ $bitacora->brigadista->apellido_paterno }} {{ $bitacora->brigadista->apellido_materno }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $bitacora->accion }}</td>
                    <td>{{ $bitacora->observaciones ?? '-' }}</td>
                    <td>{{ $bitacora->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay registros en la bitácora.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
