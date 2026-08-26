@extends('layouts.admin')

@section('content')
<div class="container">

    <h3 class="mb-4">Gestionar registros de brigadistas</h3>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif
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
        <div class="col-sm-12 d-flex gap-2 mb-3 justify-content-end">
            <button type="button" id="limpiar-filtros" class="btn btn-sm btn-outline-secondary">Limpiar</button>
            <button type="button" id="actualizar-tabla" class="btn btn-sm btn-outline-info">Actualizar</button>
            <a href="#" id="btn-exportar" class="btn btn-sm btn-outline-success">Exportar a .xlsx</a>
        </div>
    </form>

    <form action="{{ route('admin.brigadistas.accionesMasivas') }}" method="POST">
    @csrf

    <table id="tabla-brigadistas" class="table table-striped table-hover align-middle text-center" style="font-size: 14px;">
        <thead class="table-dark">
            <tr>
                <th></th>
                <th>Folio</th>
                <th>Boleta/Empleado</th>
                <th>Nombre completo</th>
                <th>Unidad Académica</th>
                <th>Programa Académico</th>
                <th>Brigada registrada</th>
                <th>Fecha de registro</th>
                <th>Opciones</th>
                <th>Modificó</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brigadistas as $brigadista)
            <tr>
                <td>
    @php
        $registroActivo = $brigadista->registros
            ->first(function ($registro) {
                return $registro->brigada && $registro->brigada->estado == 1;
            });
    @endphp

    @if($registroActivo)
        <input
            type="checkbox"
            name="registros[]"
            value="{{ $registroActivo->id }}"
            class="select-brigadista"
        >
    @endif
</td>
                <td>{{ $brigadista->id ?? '-' }}</td>
                <td>{{ $brigadista->numero_boleta }}</td>
                <td>{{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</td>
                <td>{{ $brigadista->unidad_academica }}</td>
                <td>{{ $brigadista->programa_academico }}</td>
                <td>
                    @foreach($brigadista->registros as $registro)
                        @if($registro->brigada && $registro->brigada->estado == 1)
                            <span class="badge bg-info">{{ $registro->brigada->brigada }} ({{ $registro->brigada->año }})</span>
                        @endif
                    @endforeach
                </td>
                <td>{{ $brigadista->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('admin.brigadistas.mostrarValidar', $brigadista->id) }}" class="action-link text-success me-2">
                        Ver
                    </a>
                    <a href="{{ route('admin.brigadistas.eliminar', $brigadista->id) }}"
                       class="action-link text-danger"
                       onclick="event.preventDefault(); eliminarBrigadista({{ $brigadista->id }});">
                        Eliminar
                     </a>

                </td>
                <td> {{ $brigadista->bitacoras->sortByDesc('created_at')->first()?->usuario?->name ?? '-' }}</td>
<td>

    @if($registroActivo?->status === 'validado')
        <span class="badge bg-success">Validado</span>
    @elseif($registroActivo?->status === 'cancelado')
        <span class="badge bg-danger">Cancelado</span>
    @elseif($registroActivo?->status === 'rechazado')
        <span class="badge bg-warning text-dark">Rechazado</span>
    @else
        <span class="badge bg-secondary">Pendiente</span>
    @endif
</td>

            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end gap-2 mb-3">
        <button type="submit" name="accion" value="validado" class="btn btn-success btn-sm">Validar Registro</button>
        <button type="submit" name="accion" value="rechazado" class="btn btn-warning btn-sm">Rechazar Registro</button>
        <button type="submit" name="accion" value="cancelado" class="btn btn-danger btn-sm">Cancelar Registro</button>
    </div>
</form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(document).ready(function() {

    const urlFiltrar = @json(route('admin.gestionar.filtrar'));
    const urlExportar = @json(route('admin.brigadistas.export'));

    let timer;

    $('#select-all').on('change', function() {
        $('.select-brigadista').prop('checked', this.checked);
    });

    $('#filtros-brigadistas input').on('input', function() {

        clearTimeout(timer);

        timer = setTimeout(function() {
            filtrarBrigadistas();
        }, 300);

    });

    $('#filtros-brigadistas select').on('change', function() {
        filtrarBrigadistas();
    });

    function filtrarBrigadistas() {

        $.ajax({
            url: urlFiltrar,
            type: 'GET',
            data: $('#filtros-brigadistas').serialize(),

            success: function(data) {

                $('#tabla-brigadistas tbody').html(data);

                $('#select-all').prop('checked', false);

            },

            error: function(err) {
                console.error('Error al filtrar:', err);
            }
        });
    }

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
            'Ingrese el nombre del archivo (sin extensión):',
            'brigadistas'
        );

        if (nombre === null || nombre.trim() === '') {
            return;
        }

        nombre = nombre.trim();

        if (!nombre.toLowerCase().endsWith('.xlsx')) {
            nombre += '.xlsx';
        }

        const filtros = $('#filtros-brigadistas').serialize();

        let url = urlExportar +
            '?filename=' +
            encodeURIComponent(nombre);

        if (filtros) {
            url += '&' + filtros;
        }

        window.location.href = url;

    });

});
</script>

<script>
    
    document.getElementById('select-all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.select-brigadista');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
<script>
function eliminarBrigadista(id) {
    if (confirm('¿Deseas eliminar este brigadista?')) {
        fetch('/admin/brigadistas/eliminar/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if(response.ok) {
                alert('Brigadista eliminado correctamente.');
                location.reload();
            } else {
                alert('Error al eliminar el brigadista.');
            }
        })
        .catch(error => console.error(error));
    }
}
</script>
@endsection
