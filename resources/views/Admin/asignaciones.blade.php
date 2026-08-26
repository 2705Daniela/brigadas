@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2>Asignaciones de Brigadistas</h2>

    {{-- Filtros --}}
    <form id="filtros-asignaciones" class="row g-2 mb-3">
        <div class="col-sm-3">
            <input type="text" name="numero_boleta" class="form-control form-control-sm" placeholder="Buscar por Boleta/Empleado">
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
            <button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button>
            <button type="button" id="limpiar-filtros" class="btn btn-sm btn-outline-secondary">Limpiar</button>
            <button type="button" id="actualizar-tabla" class="btn btn-sm btn-outline-info">Actualizar</button>
        </div>
    </form>

    {{-- Tabla de asignaciones --}}
    <table class="table table-striped table-hover align-middle text-center" style="font-size: 12px;">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Boleta/Empleado</th>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Unidad académica</th>
                <th>Estado</th>
                <th>Municipio</th>
                <th>Proyecto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="body-asignaciones">
            @foreach($brigadistas as $brigadista)
                @php $asignacion = $brigadista->asignaciones->first(); @endphp
                <tr data-id="{{ $brigadista->id }}">
                    <td>{{ $brigadista->id }}</td>
                    <td>{{ $brigadista->numero_boleta }}</td>
                    <td>{{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</td>
                    <td>{{ $brigadista->programa_academico }}</td>
                    <td>{{ $brigadista->unidad_academica }}</td>
                    <td>
                        <select class="form-select estado-select" {{ $asignacion ? 'disabled' : '' }}>
                            <option value="">Selecciona estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->estado }}" {{ optional($asignacion)->estado_asignado == $estado->estado ? 'selected' : '' }}>{{ $estado->estado }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select 
                                  class="form-select municipio-select" data-selected="{{ optional($asignacion)->municipio_asignado }}"{{ $asignacion ? 'disabled' : '' }}
>                                <option value="">Selecciona municipio</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-select proyecto-select" {{ $asignacion ? 'disabled' : '' }}>
                            <option value="">-- Selecciona un proyecto --</option>
                            @foreach($proyectos as $proyecto)
                                <option value="{{ $proyecto }}" {{ optional($asignacion)->proyecto_asignado == $proyecto ? 'selected' : '' }}>{{ $proyecto }}</option>
                            @endforeach
                        </select>
                    </td>
        
                    <td>
                        <button type="button" class="btn btn-link text-success btn-guardar" {{ $asignacion ? 'd-none' : '' }}>Guardar</button>
                        <button type="button" class="btn btn-link text-warning btn-editar" {{ $asignacion ? '' : 'd-none' }}>Editar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        <form action="{{ route('admin.asignaciones.guardar') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Guardar todas las asignaciones</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function() {

    function actualizarMunicipios(row, estado, municipioSeleccionado = null) {
        const municipioSelect = row.find('.municipio-select');
        municipioSelect.html('<option value="">Selecciona municipio</option>');

        if (estado) {
            fetch(`/municipios/${encodeURIComponent(estado)}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(m => {
                        const selected = (municipioSeleccionado && municipioSeleccionado === m) ? 'selected' : '';
                        municipioSelect.append(`<option value="${m}" ${selected}>${m}</option>`);
                    });
                })
                .catch(err => console.error('Error cargando municipios:', err));
        }
    }
    // Inicializar municipios al cargar la página
    $('tr[data-id]').each(function() {
        const row = $(this);
        const estado = row.find('.estado-select').val();
        const municipio = row.find('.municipio-select').data('selected');
        if (estado) {
            actualizarMunicipios(row, estado, municipio);
        }
    });

    // Actualizar municipios al cambiar el estado
    $(document).on('change', '.estado-select', function() {
        const row = $(this).closest('tr');
        const estado = $(this).val();
        actualizarMunicipios(row, estado);
    });

});
</script>
<script>
$(document).ready(function() {

    // Guardar fila individual
    $(document).on('click', '.btn-guardar', function() {
        const row = $(this).closest('tr');
        const brigadistaId = row.data('id');
        const estado = row.find('.estado-select').val();
        const municipio = row.find('.municipio-select').val();
        const proyecto = row.find('.proyecto-select').val();

        if (!estado || !municipio || !proyecto) {
            alert('Selecciona Estado, Municipio y Proyecto antes de guardar.');
            return;
        }

        $.ajax({
            url: "{{ route('admin.asignaciones.guardarFila') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: brigadistaId,
                estado: estado,
                municipio: municipio,
                proyecto: proyecto
            },
            success: function(response) {
                row.find('select').prop('disabled', true);
                row.find('.btn-guardar').addClass('d-none');
                row.find('.btn-editar').removeClass('d-none');
                row.find('.modifico').text(response.modifico);
            },
            error: function(err) {
                console.error('Error al guardar la asignación:', err);
                alert('Error al guardar la asignación.');
            }
        });
    });

    // Editar fila individual
    $(document).on('click', '.btn-editar', function() {
        const row = $(this).closest('tr');
        row.find('select').prop('disabled', false);
        $(this).addClass('d-none');
        row.find('.btn-guardar').removeClass('d-none');
    });

});
</script>
<script>
$(document).ready(function() {

    const urlFiltrar = "{{ route('admin.asignaciones.filtrar') }}";

    // --- Función para recargar municipios según estado ---
    function actualizarMunicipios(row, estado, municipioSeleccionado = null) {
        const municipioSelect = row.find('.municipio-select');
        municipioSelect.html('<option value="">Selecciona municipio</option>');

        if (estado) {
            fetch(`/municipios/${encodeURIComponent(estado)}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(m => {
                        const selected = (municipioSeleccionado && municipioSeleccionado === m) ? 'selected' : '';
                        municipioSelect.append(`<option value="${m}" ${selected}>${m}</option>`);
                    });
                })
                .catch(err => console.error('Error cargando municipios:', err));
        }
    }

    // --- Función para bloquear los selects ya asignados ---
    function bloquearSelectsGuardados() {
        $('#body-asignaciones').find('select[data-bloqueado="1"]').each(function() {
            $(this).prop('disabled', true);
        });
    }

    // --- Función principal de filtrado ---
    function filtrarAsignaciones() {
        $.ajax({
            url: urlFiltrar,
            type: 'GET',
            data: $('#filtros-asignaciones').serialize(),
            success: function(html) {
                // reemplaza el contenido de la tabla
                $('#body-asignaciones').html(html);

                // recarga municipios de acuerdo al estado guardado
                $('tr[data-brigadista-id]').each(function() {
                    const row = $(this);
                    const estado = row.find('.estado-select').val();
                    const municipio = row.find('.municipio-select').data('selected');
                    if (estado) {
                        actualizarMunicipios(row, estado, municipio);
                    }
                });

                // vuelve a bloquear los selects asignados
                bloquearSelectsGuardados();
            },
            error: function(err) {
                console.error('Error al filtrar asignaciones:', err);
            }
        });
    }

    $('#filtros-asignaciones input').on('input', function() {
    filtrarAsignaciones();
});

$('#filtros-asignaciones select').on('change', function() {
    filtrarAsignaciones();
});

$('#limpiar-filtros').on('click', function() {
    $('#filtros-asignaciones')[0].reset();
    filtrarAsignaciones();
});

$('#actualizar-tabla').on('click', function() {
    filtrarAsignaciones();
});

bloquearSelectsGuardados();
});
</script>

@endsection
