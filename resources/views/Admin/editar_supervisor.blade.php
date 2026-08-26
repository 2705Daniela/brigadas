@extends('layouts.admin')

@section('content')
<div class="container mt-5">

    <h3 class="mb-4">Editar supervisor</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.supervisores.actualizar', $supervisor->id) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">Nombre</label>
                <input
                    type="text"
                    name="nombre"
                    class="form-control"
                    value="{{ old('nombre', $supervisor->nombre) }}"
                    required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido paterno</label>
                <input
                    type="text"
                    name="apellido_paterno"
                    class="form-control"
                    value="{{ old('apellido_paterno', $supervisor->apellido_paterno) }}"
                    required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido materno</label>
                <input
                    type="text"
                    name="apellido_materno"
                    class="form-control"
                    value="{{ old('apellido_materno', $supervisor->apellido_materno) }}"
                    required>
            </div>

        </div>

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">CURP</label>
                <input
                    type="text"
                    name="curp"
                    class="form-control"
                    maxlength="18"
                    value="{{ old('curp', $supervisor->curp) }}"
                    required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Correo</label>
                <input
                    type="email"
                    name="correo"
                    class="form-control"
                    value="{{ old('correo', $supervisor->correo) }}"
                    required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Número telefónico</label>
                <input
                    type="text"
                    name="telefono"
                    class="form-control"
                    value="{{ old('telefono', $supervisor->telefono) }}"
                    required>
            </div>

        </div>

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">CLABE interbancaria</label>
                <input
                    type="text"
                    name="clabe"
                    class="form-control"
                    value="{{ old('clabe', $supervisor->clabe) }}"
                    required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Entidad bancaria</label>

                <select name="banco" class="form-select" required>

                    <option value="">Selecciona banco</option>

                    <option value="BBVA"
                        {{ old('banco', $supervisor->banco) == 'BBVA' ? 'selected' : '' }}>
                        BBVA
                    </option>

                    <option value="SANTANDER"
                        {{ old('banco', $supervisor->banco) == 'SANTANDER' ? 'selected' : '' }}>
                        Santander
                    </option>

                    <option value="BANORTE"
                        {{ old('banco', $supervisor->banco) == 'BANORTE' ? 'selected' : '' }}>
                        Banorte
                    </option>

                    <option value="HSBC"
                        {{ old('banco', $supervisor->banco) == 'HSBC' ? 'selected' : '' }}>
                        HSBC
                    </option>

                    <option value="BANCO AZTECA"
                        {{ old('banco', $supervisor->banco) == 'BANCO AZTECA' ? 'selected' : '' }}>
                        Banco Azteca
                    </option>

                    <option value="BANCOPPEL"
                        {{ old('banco', $supervisor->banco) == 'BANCOPPEL' ? 'selected' : '' }}>
                        Bancoppel
                    </option>

                </select>
            </div>

        </div>

        <div class="mt-3">

            <button type="submit" class="btn btn-primary">
                Guardar cambios
            </button>

            <a
                href="{{ route('admin.lista_supervisores') }}"
                class="btn btn-secondary">
                Cancelar
            </a>

        </div>

    </form>

</div>
@endsection