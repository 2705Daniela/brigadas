@extends('layouts.admin')

@section('content')
<div class="container mt-5">

    <h3 class="mb-4">Registro de supervisores</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @php
        $brigadaActiva = \App\Models\Brigada::where('estado', 1)->first();
    @endphp

    @if(!$brigadaActiva)
        <div class="alert alert-danger">
            No hay brigada activa. No se puede registrar un supervisor.
        </div>
    @endif

    <form action="{{ route('supervisores.store') }}" method="POST">
        @csrf

        @if($brigadaActiva)
            <input type="hidden" name="brigada_id" value="{{ $brigadaActiva->id }}">

            <div class="mb-3">
                <p class="form-control-plaintext">
                    Brigada activa:
                    <strong>
                        {{ $brigadaActiva->brigada }} - {{ $brigadaActiva->año }}
                    </strong>
                </p>
            </div>
        @endif

        <fieldset {{ !$brigadaActiva ? 'disabled' : '' }}>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label for="nombre" class="form-label">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        class="form-control"
                        value="{{ old('nombre') }}"
                        required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="apellido_paterno" class="form-label">
                        Apellido paterno
                    </label>

                    <input
                        type="text"
                        name="apellido_paterno"
                        id="apellido_paterno"
                        class="form-control"
                        value="{{ old('apellido_paterno') }}"
                        required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="apellido_materno" class="form-label">
                        Apellido materno
                    </label>

                    <input
                        type="text"
                        name="apellido_materno"
                        id="apellido_materno"
                        class="form-control"
                        value="{{ old('apellido_materno') }}"
                        required>
                </div>

            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label for="curp" class="form-label">
                        CURP
                    </label>

                    <input
                        type="text"
                        name="curp"
                        id="curp"
                        class="form-control"
                        value="{{ old('curp') }}"
                        maxlength="18"
                        required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="correo" class="form-label">
                        Correo
                    </label>

                    <input
                        type="email"
                        name="correo"
                        id="correo"
                        class="form-control"
                        value="{{ old('correo') }}"
                        required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="telefono" class="form-label">
                        Número telefónico
                    </label>

                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        class="form-control"
                        value="{{ old('telefono') }}"
                        required>
                </div>

            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label for="clabe" class="form-label">
                        CLABE interbancaria
                    </label>

                    <input
                        type="text"
                        name="clabe"
                        id="clabe"
                        class="form-control"
                        value="{{ old('clabe') }}"
                        required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="banco" class="form-label">
                        Entidad bancaria
                    </label>

                    <select name="banco" id="banco" class="form-select" required>
                        <option value="">Selecciona banco</option>
                        <option value="BBVA">BBVA</option>
                        <option value="SANTANDER">Santander</option>
                        <option value="BANORTE">Banorte</option>
                        <option value="HSBC">HSBC</option>
                        <option value="BANCO AZTECA">Banco Azteca</option>
                        <option value="BANCOPPEL">Bancoppel</option>
                    </select>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">
                Registrar supervisor
            </button>

        </fieldset>

    </form>

</div>
@endsection