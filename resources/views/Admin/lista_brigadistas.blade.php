@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Lista de Brigadistas Registrados</h3>

    {{-- Filtros --}}
    <form id="filtros-brigadistas" class="row g-2 mb-3 align-items-center">
        {{-- Filtro por Boleta --}}
        <div class="col-auto">
            <input type="text" name="numero_boleta" class="form-control form-control-sm"
                   placeholder="Buscar por Boleta">
        </div>

        {{-- Filtro por Nombre completo --}}
        <div class="col-auto">
            <input type="text" name="nombre_completo" class="form-control form-control-sm"
                   placeholder="Buscar por Nombre">
        </div>

        {{-- Botones --}}
        <div class="col-auto d-flex gap-2">
            <button type="button" id="limpiar-filtros" class="btn btn-sm btn-outline-secondary">Limpiar</button>
            <button type="button" id="actualizar-tabla" class="btn btn-sm btn-outline-info">Actualizar</button>
        </div>
    </form>

    {{-- Tabla de brigadistas --}}
    <div class="table-responsive">
        <table id="tabla-brigadistas" class="table table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre completo</th>
                    <th>Número de Boleta/Empleado</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brigadistas as $brigadista)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</td>
                        <td>{{ $brigadista->numero_boleta }}</td>
                        <td>{{ $brigadista->correo }}</td>
                        <td>
                            <a href="{{ route('admin.brigadistas.restablecer', $brigadista->id) }}"
                               class="btn btn-sm btn-info">Restablecer contraseña</a>
                            <form action="{{ route('admin.brigadistas.eliminar', $brigadista->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $brigadistas->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('filtros-brigadistas');
    const tabla = document.querySelector('#tabla-brigadistas tbody');
    const urlFiltrar = "{{ route('admin.lista.filtros') }}";

    let timeout = null;

    function filtrarBrigadistas() {

        const params = new URLSearchParams();

        form.querySelectorAll('input').forEach(function (input) {
            if (input.value.trim() !== '') {
                params.append(input.name, input.value);
            }
        });

        fetch(urlFiltrar + '?' + params.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Error HTTP: ' + response.status);
            }

            return response.text();
        })
        .then(function (html) {
            tabla.innerHTML = html;
        })
        .catch(function (error) {
            console.error('Error al filtrar brigadistas:', error);
        });
    }

    form.querySelectorAll('input').forEach(function (input) {

        input.addEventListener('input', function () {

            clearTimeout(timeout);

            timeout = setTimeout(function () {
                filtrarBrigadistas();
            }, 500);

        });

    });

    document
        .getElementById('limpiar-filtros')
        .addEventListener('click', function () {

            form.reset();
            filtrarBrigadistas();

        });

    document
        .getElementById('actualizar-tabla')
        .addEventListener('click', function () {

            filtrarBrigadistas();

        });

});
</script>
@endsection

