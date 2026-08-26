@extends('layouts.admin')

@section('content')
<div class="container">

    <h3 class="mb-4">Todos los registros</h3>

    {{-- Filtros --}}
    <form id="filtros-brigadistas" class="row g-2 mb-3">
        <div class="col-sm-3">
            <input type="text" name="numero_boleta" class="form-control form-control-sm" placeholder="Buscar por Boleta" value="{{ request('numero_boleta') }}">
        </div>
        <div class="col-sm-3">
            <select name="unidad_academica" class="form-select form-select-sm">
                <option value="">Unidades Académicas</option>
                @foreach($brigadistas->pluck('unidad_academica')->unique() as $unidad)
                    <option value="{{ $unidad }}">{{ $unidad }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <select name="programa_academico" class="form-select form-select-sm">
                <option value="">Programas Académicos</option>
                @foreach($brigadistas->pluck('programa_academico')->unique() as $programa)
                    <option value="{{ $programa }}">{{ $programa }}</option>
                @endforeach
            </select>
        </div>
    <div class="col-sm-3">
    <select name="brigada_id" class="form-select form-select-sm">
        <option value="">Seleccionar brigada</option>
        @if(isset($brigadas) && $brigadas->count() > 0)
            @foreach($brigadas as $brigada)
                <option value="{{ $brigada->id }}">
                    {{ $brigada->brigada }} ({{ $brigada->año }})
                </option>
            @endforeach
        @endif
    </select>
</div>


        <div class="col-sm-12 d-flex gap-2 mb-3 justify-content-end">
          <button type="button" id="limpiar-filtros" class="btn btn-sm btn-outline-secondary">Limpiar</button>
          <button type="button" id="actualizar-tabla" class="btn btn-sm btn-outline-info">Actualizar</button>
          <a href="#" id="btn-exportar" class="btn btn-sm btn-outline-success">Exportar a .xlsx</a>
        </div>
    </form>

    {{-- Tabla --}}
    <table id="tabla-brigadistas" class="table table-striped table-hover align-middle text-center" style="font-size: 14px;">
        <thead class="table-dark">
            <tr>
                <th></th>
                <th>Folio</th>
                <th>Número de Boleta/Empleado</th>
                <th>Nombre completo</th>
                <th>Unidad Académica</th>
                <th>Programa Académico</th>
                <th>Brigada registrada</th>
                <th>Fecha de registro</th>
                <th>Acciones</th>
                <th>Modificó</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brigadistas as $brigadista)
            <tr>
                <td>
                    <input type="checkbox" name="brigadistas[]" value="{{ $brigadista->id }}" class="select-brigadista">
                </td>
                <td>{{ $brigadista->id ?? '-' }}</td>
                <td>{{ $brigadista->numero_boleta }}</td>
                <td>{{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</td>
                <td>{{ $brigadista->unidad_academica }}</td>
                <td>{{ $brigadista->programa_academico }}</td>
                <td>
    @foreach($brigadista->registros as $registro)
        @if($registro->brigada) 
            <span class="badge bg-info">{{ $registro->brigada->brigada }} ({{ $registro->brigada->año }})</span>
        @endif
    @endforeach
</td>
                <td>{{ $brigadista->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('admin.brigadistas.mostrarValidar', $brigadista->id) }}" 
   class="action-link border-0 bg-transparent p-0 text-success" 
   style="cursor:pointer;">
   Ver
</a>
                    <form action="{{ route('admin.brigadistas.eliminar', $brigadista->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-link border-0 bg-transparent p-0" style="cursor: pointer;">Eliminar</button>
                    </form>
                </td>
                <td>{{ $brigadista->bitacoras->last()->usuario->name ?? '-' }}</td>
                <td>
   @foreach($brigadista->registros as $registro)
    @php
        $estatus = $registro->status ?? 'pendiente';
    @endphp

    @if($estatus === 'validado')
        <span class="badge bg-success">Validado</span>
    @elseif($estatus === 'cancelado')
        <span class="badge bg-danger">Cancelado</span>
    @elseif($estatus === 'rechazado')
        <span class="badge bg-warning text-dark">Rechazado</span>
    @else
        <span class="badge bg-secondary">Pendiente</span>
    @endif
@endforeach


            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(document).ready(function() {

    let urlFiltrar = @json(route('admin.registros_completo.filtrar'));

    function filtrarBrigadistas() {
        $.ajax({
            url: urlFiltrar,
            type: 'GET',
            data: $('#filtros-brigadistas').serialize(),
            success: function(data) {
                $('#tabla-brigadistas tbody').html(data);
            },
            error: function(err) {
                console.error('Error al filtrar:', err);
            }
        });
    }

    $('#filtros-brigadistas input').on('input', function() {
        filtrarBrigadistas();
    });

    $('#filtros-brigadistas select').on('change', function() {
        filtrarBrigadistas();
    });

    $('#limpiar-filtros').on('click', function() {
        $('#filtros-brigadistas')[0].reset();
        filtrarBrigadistas();
    });

    $('#actualizar-tabla').on('click', function() {
        filtrarBrigadistas();
    });

    $('#btn-exportar').on('click', function(e) {
        e.preventDefault();

        let nombre = prompt(
            "Ingrese el nombre del archivo (sin extensión):",
            "brigadistas"
        );

        if (nombre === null || nombre.trim() === '') {
            return;
        }

        nombre = nombre.trim();

        if (!nombre.endsWith('.xlsx')) {
            nombre += '.xlsx';
        }

        let filtros = $('#filtros-brigadistas').serialize();

        let url = "{{ route('admin.brigadistas.export') }}"
            + "?filename="
            + encodeURIComponent(nombre)
            + "&"
            + filtros;

        window.location.href = url;
    });

});
</script>
@endsection
