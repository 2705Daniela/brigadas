@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Restablecer contraseña del brigadista</h2>

    {{-- Información del brigadista --}}
    <div class="mb-3">
        <strong>Nombre:</strong> {{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}
    </div>
    <div class="mb-3">
        <strong>Correo:</strong> {{ $brigadista->correo }}
    </div>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Corrige los errores:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de restablecimiento --}}
    <form action="{{ route('admin.brigadistas.restablecer.update', $brigadista->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.brigadistas.lista') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Restablecer contraseña</button>
        </div>
    </form>
</div>
@endsection
