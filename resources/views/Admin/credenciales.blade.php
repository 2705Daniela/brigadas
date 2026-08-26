@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h1>Credenciales</h1>

    <form id="filtros-credenciales" class="row g-2 mb-3">

    <div class="col-sm-3">
        <input
            type="text"
            name="boleta"
            class="form-control form-control-sm"
            placeholder="Buscar por Boleta"
            value="{{ request('boleta') }}">
    </div>

    <div class="col-sm-3">
        <input
            type="text"
            name="folio"
            class="form-control form-control-sm"
            placeholder="Buscar por Folio"
            value="{{ request('folio') }}">
    </div>

    <div class="col-sm-3">
        <select name="municipio" class="form-select form-select-sm">
            <option value="">Todos los municipios</option>

            @foreach($municipios as $municipio)
                <option
                    value="{{ $municipio }}"
                    {{ request('municipio') == $municipio ? 'selected' : '' }}>
                    {{ $municipio }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-3">
        <button
            type="button"
            id="limpiar-filtros"
            class="btn btn-sm btn-outline-secondary">
            Limpiar
        </button>
    </div>
</form>

    </form>

    <div class="d-flex justify-content-end mb-3">

        <button
            type="button"
            id="descargar-seleccionadas"
            class="btn btn-sm btn-success">
            Descargar seleccionadas
        </button>

    </div>

    <table class="table table-striped table-hover align-middle text-center">

        <thead class="table-dark">
            <tr>

                <th>
                    <input
                        type="checkbox"
                        id="seleccionar-todos">
                </th>

                <th>Folio</th>

                <th>Número de Boleta / Empleado</th>

                <th>Nombre completo</th>

                <th>Brigada registrada</th>

                <th>Fecha de registro</th>

                <th>Acciones</th>

            </tr>
        </thead>

        <tbody id="tabla-credenciales">

            @foreach($brigadistas as $brigadista)

                <tr>

                    <td>
                        <input
                            type="checkbox"
                            class="credencial-checkbox"
                            value="{{ $brigadista->id }}">
                    </td>

                    <td>
                        {{ $brigadista->id }}
                    </td>

                    <td>
                        {{ $brigadista->numero_boleta }}
                    </td>

                    <td>
                        {{ $brigadista->nombre }}
                        {{ $brigadista->apellido_paterno }}
                        {{ $brigadista->apellido_materno }}
                    </td>

                    <td>

                        @foreach($brigadista->registros as $registro)

                            @if($registro->brigada)

                                <span class="badge bg-info">

                                    {{ $registro->brigada->brigada }}
                                    ({{ $registro->brigada->año }})

                                </span>

                            @endif

                        @endforeach

                    </td>

                    <td>
                        {{ $brigadista->created_at->format('d/m/Y') }}
                    </td>

                    <td>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalCredencial{{ $brigadista->id }}">

                            Ver credencial

                        </button>

                    </td>

                </tr>

                <div
                    class="modal fade"
                    id="modalCredencial{{ $brigadista->id }}"
                    tabindex="-1">

                    <div class="modal-dialog modal-xl modal-dialog-centered">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h5 class="modal-title">
                                    Credencial digital
                                </h5>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                                </button>

                            </div>

                            <div
                                class="modal-body p-0"
                                style="height: 80vh;">

                                <iframe
                                    src="{{ route('admin.credencial.pdf', $brigadista->id) }}"
                                    width="100%"
                                    height="100%"
                                    style="border: none;">
                                </iframe>

                            </div>

                            <div class="modal-footer">

                                <a
                                    href="{{ route('admin.credencial.pdf', $brigadista->id) }}"
                                    target="_blank"
                                    class="btn btn-outline-success">

                                    Descargar PDF

                                </a>

                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                    Cerrar

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </tbody>

    </table>

</div>
@endsection


@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('filtros-credenciales');

    const inputs = form.querySelectorAll('input');

    const selects = form.querySelectorAll('select');

    function aplicarFiltros() {

        const params = new URLSearchParams();

        form.querySelectorAll('input, select').forEach(function (element) {

            if (element.value.trim() !== '') {
                params.set(element.name, element.value);
            }

        });

        const url = "{{ route('admin.credenciales') }}";

        window.location.href =
            params.toString()
                ? url + '?' + params.toString()
                : url;
    }


    selects.forEach(function (select) {

        select.addEventListener('change', function () {
            aplicarFiltros();
        });

    });


    let timeout;

    inputs.forEach(function (input) {

        input.addEventListener('input', function () {

            clearTimeout(timeout);

            timeout = setTimeout(function () {
                aplicarFiltros();
            }, 500);

        });

    });


    document
        .getElementById('limpiar-filtros')
        .addEventListener('click', function () {

            window.location.href = "{{ route('admin.credenciales') }}";

        });

});
</script>

@endsection