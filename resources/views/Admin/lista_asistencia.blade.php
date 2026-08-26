@extends('layouts.admin')

@section('content')
<div class="container">

    <h3 class="mb-4">Listas de Asistencia</h3>

    {{-- Filtros --}}
<form id="filtros-brigadistas" class="row g-2 mb-3">

    <div class="col-sm-6">
        <select name="municipio" class="form-select form-select-sm">
            <option value="">-- Todos los Municipios --</option>
            @foreach($municipios as $mun)
                <option value="{{ $mun }}"
                    {{ request('municipio') == $mun ? 'selected' : '' }}>
                    {{ $mun }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12 d-flex gap-2 justify-content-end mt-2">
        <button type="submit" class="btn btn-sm btn-outline-primary">
            Filtrar
        </button>

        <button type="button" id="limpiar-filtros"
                class="btn btn-sm btn-outline-secondary">
            Limpiar
        </button>

        <button type="button" id="actualizar-tabla"
                class="btn btn-sm btn-outline-info">
            Actualizar
        </button>

        <a id="btn-pdf"
        href="{{ route('admin.lista_asistencia.pdf') }}"
        class="btn btn-sm btn-outline-danger"
        target="_blank">
        Descargar PDF
        </a>
    </div>

</form>

    {{-- Tabla --}}
    <h5 class="mt-4">Supervisores</h5>
    <table id="tabla-supervisores" class="table table-bordered table-striped text-center">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Municipio asignado</th>
            <th>Rol</th>
        </tr>
    </thead>
    <tbody>
        @forelse($supervisores as $supervisor)
            <tr>
                <td>{{ $supervisor->nombre }}</td>
                <td>{{ $supervisor->correo }}</td>
                <td>{{ $supervisor->telefono }}</td>
                <td>{{ $supervisor->asignaciones->pluck('municipio_asignado')->join(', ') }}</td>
                <td>SUPERVISOR</td>
            </tr>
        @empty
            <tr><td colspan="6">No hay supervisores asignados.</td></tr>
        @endforelse
    </tbody>
</table>


    <h5 class="mt-4">Brigadistas</h5>
    <table id="tabla-brigadistas" class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Boleta</th>
                    <th>Nombre</th>
                    <th>Escuela</th>
                    <th>Carrera</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Municipio</th>
                    <th>Proyecto</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brigadistas as $brigadista)
                    @php $asignacion = $brigadista->asignaciones->first(); @endphp
                    <tr>
                        <td>{{ $brigadista->numero_boleta }}</td>
                        <td>{{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</td>
                        <td>{{ $brigadista->unidad_academica }}</td>
                        <td>{{ $brigadista->programa_academico }}</td>
                        <td>{{ $brigadista->telefono }}</td>
                        <td>{{ $brigadista->correo }}</td>
                        <td>{{ $asignacion->municipio_asignado ?? '-' }}</td>
                        <td>{{ $asignacion->proyecto_asignado ?? '-' }}</td>
                        <td>{{ $brigadista->registros->first()->rol_deseado ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9">No se encontraron brigadistas con esos filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>

$(document).ready(function () {

    const urlFiltrar = @json(route('admin.lista_asistencia.filtrar'));
    const urlPDF = @json(route('admin.lista_asistencia.pdf'));


    // =====================================================
    // ACTUALIZAR URL DEL PDF
    // =====================================================

    function actualizarUrlPDF() {

        const municipio = $('#filtros-brigadistas select[name="municipio"]').val();

        let url = urlPDF;

        if (municipio) {
            url += '?municipio=' + encodeURIComponent(municipio);
        }

        $('#btn-pdf').attr('href', url);
    }


    // =====================================================
    // FILTRAR LISTAS
    // =====================================================

    function filtrarListas() {

        $.ajax({

            url: urlFiltrar,

            type: 'GET',

            data: $('#filtros-brigadistas').serialize(),

            success: function (data) {

                console.log('Filtro aplicado:', data);

                $('#tabla-brigadistas tbody')
                    .html(data.brigadistasHtml);

                $('#tabla-supervisores tbody')
                    .html(data.supervisoresHtml);

            },

            error: function (xhr) {

                console.error('Error en el filtrado:', xhr);

                $('#tabla-brigadistas tbody').html(
                    '<tr>' +
                    '<td colspan="9" class="text-danger">' +
                    'Error al obtener los brigadistas.' +
                    '</td>' +
                    '</tr>'
                );

                $('#tabla-supervisores tbody').html(
                    '<tr>' +
                    '<td colspan="6" class="text-danger">' +
                    'Error al obtener los supervisores.' +
                    '</td>' +
                    '</tr>'
                );

            }

        });
    }


    // =====================================================
    // FILTRAR AUTOMÁTICAMENTE AL SELECCIONAR MUNICIPIO
    // =====================================================

    $('#filtros-brigadistas select[name="municipio"]').on('change', function () {

        filtrarListas();

        actualizarUrlPDF();

    });


    // =====================================================
    // BOTÓN FILTRAR
    // =====================================================

    $('#filtros-brigadistas').on('submit', function (e) {

        e.preventDefault();

        filtrarListas();

        actualizarUrlPDF();

    });



    $('#limpiar-filtros').on('click', function () {

        $('#filtros-brigadistas')[0].reset();

        filtrarListas();

        actualizarUrlPDF();

    });


    $('#actualizar-tabla').on('click', function () {

        filtrarListas();

        actualizarUrlPDF();

    });

    actualizarUrlPDF();

});

</script>

@endsection