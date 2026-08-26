@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2>Asignaciones de Supervisores</h2>

    {{-- Filtros --}}
    <form id="filtros-asignaciones" class="row g-2 mb-3">
    <div class="col-sm-6">
        <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Buscar por nombre">
    </div>
        <div class="col-sm-12 d-flex gap-2 mb-3 justify-content-end">
            <button type="button" id="limpiar-filtros" class="btn btn-sm btn-outline-secondary">Limpiar</button>
            <button type="button" id="actualizar-tabla" class="btn btn-sm btn-outline-info">Actualizar</button>
        </div>
    </form>

    {{-- Tabla de asignaciones --}}
    <table class="table table-striped table-hover align-middle text-center" style="font-size: 12px;">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th>Municipio</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody id="body-asignaciones">
        @foreach($supervisores as $supervisor)

            @php
                $asignacion = $supervisor->asignaciones->first();
            @endphp

            <tr data-id="{{ $supervisor->id }}">

                <td>{{ $supervisor->id }}</td>

                <td>
                    {{ $supervisor->nombre }}
                    {{ $supervisor->apellido_paterno }}
                    {{ $supervisor->apellido_materno }}
                </td>

                <td>{{ $supervisor->correo }}</td>

                <td>{{ $supervisor->telefono }}</td>

                {{-- ESTADO --}}
                <td>
                    <select
                        class="form-select estado-select"
                        {{ $asignacion ? 'disabled' : '' }}
                    >
                        <option value="">Selecciona estado</option>

                        @foreach($estados as $estado)

                            <option
                                value="{{ $estado->estado }}"
                                {{ optional($asignacion)->estado_asignado == $estado->estado ? 'selected' : '' }}
                            >
                                {{ $estado->estado }}
                            </option>

                        @endforeach
                    </select>
                </td>

                {{-- MUNICIPIO --}}
                <td>
                    <select
                        class="form-select municipio-select"
                        data-selected="{{ optional($asignacion)->municipio_asignado }}"
                        {{ $asignacion ? 'disabled' : '' }}
                    >
                        <option value="">Selecciona municipio</option>
                    </select>
                </td>


                {{-- ACCIONES --}}
                <td>

                    <button
                        type="button"
                        class="btn btn-link text-success btn-guardar"
                        {{ $asignacion ? 'd-none' : '' }}
                    >
                        Guardar
                    </button>

                    <button
                        type="button"
                        class="btn btn-link text-warning btn-editar"
                        {{ $asignacion ? '' : 'd-none' }}
                    >
                        Editar
                    </button>

                </td>

            </tr>

        @endforeach
    </tbody>
</table>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(document).ready(function () {

    function actualizarMunicipios(row, estado, municipioSeleccionado = null) {
        const municipioSelect = row.find('.municipio-select');

        municipioSelect.html('<option value="">Selecciona municipio</option>');

        if (!estado) {
            return;
        }

        fetch('/municipios/' + encodeURIComponent(estado))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener municipios');
                }
                return response.json();
            })
            .then(data => {
                data.forEach(municipio => {
                    const selected =
                        municipioSeleccionado &&
                        municipioSeleccionado === municipio
                            ? 'selected'
                            : '';

                    municipioSelect.append(
                        `<option value="${municipio}" ${selected}>${municipio}</option>`
                    );
                });
            })
            .catch(error => {
                console.error(error);
            });
    }

    $('tr[data-id]').each(function () {
        const row = $(this);
        const estado = row.find('.estado-select').val();
        const municipio = row.find('.municipio-select').data('selected');

        if (estado) {
            actualizarMunicipios(row, estado, municipio);
        }
    });

    $(document).on('change', '.estado-select', function () {
        const row = $(this).closest('tr');
        const estado = $(this).val();

        actualizarMunicipios(row, estado);
    });

    $(document).on('click', '.btn-guardar', function () {

        const button = $(this);
        const row = button.closest('tr');

        const supervisorId = row.data('id');
        const estado = row.find('.estado-select').val();
        const municipio = row.find('.municipio-select').val();

        if (!supervisorId) {
            alert('No se encontró el supervisor.');
            return;
        }

        if (!estado) {
            alert('Selecciona un estado.');
            return;
        }

        if (!municipio) {
            alert('Selecciona un municipio.');
            return;
        }

        button.prop('disabled', true);

        $.ajax({
            url: "{{ route('admin.asignaciones_supervisores.guardarFila') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: supervisorId,
                estado: estado,
                municipio: municipio
            },
            success: function (response) {

                row.find('.estado-select').prop('disabled', true);
                row.find('.municipio-select').prop('disabled', true);

                button.addClass('d-none');
                row.find('.btn-editar').removeClass('d-none');

                row.find('.modifico').text(
                    response.modifico ?? '-'
                );

                button.prop('disabled', false);
            },
            error: function (xhr) {

                console.error(xhr.responseText);

                let mensaje = 'Error al guardar la asignación.';

                if (xhr.responseJSON && xhr.responseJSON.error) {
                    mensaje = xhr.responseJSON.error;
                }

                alert(mensaje);

                button.prop('disabled', false);
            }
        });
    });

    $(document).on('click', '.btn-editar', function () {

        const row = $(this).closest('tr');

        row.find('.estado-select').prop('disabled', false);
        row.find('.municipio-select').prop('disabled', false);

        $(this).addClass('d-none');

        row.find('.btn-guardar').removeClass('d-none');
    });

    const urlFiltrar =
        "{{ route('admin.asignacion_supervisores.filtrar') }}";

    function filtrarSupervisores() {

        $.ajax({
            url: urlFiltrar,
            type: 'GET',
            data: $('#filtros-asignaciones').serialize(),

            success: function (html) {

                $('#body-asignaciones').html(html);

                $('#body-asignaciones tr[data-id]').each(function () {

                    const row = $(this);

                    const estado =
                        row.find('.estado-select').val();

                    const municipio =
                        row.find('.municipio-select').data('selected');

                    if (estado) {
                        actualizarMunicipios(
                            row,
                            estado,
                            municipio
                        );
                    }
                });
            },

            error: function (xhr) {
                console.error(
                    'Error al filtrar supervisores:',
                    xhr.responseText
                );
            }
        });
    }

    $('#filtros-asignaciones input').on('input', function () {
        filtrarSupervisores();
    });

    $('#limpiar-filtros').on('click', function () {
        $('#filtros-asignaciones')[0].reset();
        filtrarSupervisores();
    });

    $('#actualizar-tabla').on('click', function () {
        filtrarSupervisores();
    });

});
</script>
@endsection