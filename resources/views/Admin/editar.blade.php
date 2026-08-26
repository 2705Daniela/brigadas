@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Editar usuario</h2>

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

    {{-- Formulario de edición --}}
    <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre completo</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ old('name', $usuario->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="{{ old('email', $usuario->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña (opcional)</label>
            <input type="password" name="password" id="password" class="form-control" 
                   placeholder="Dejar en blanco para no cambiar">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                   placeholder="Dejar en blanco para no cambiar">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rol</label>
            <select name="role" id="role" class="form-select" required>
                <option value="Administrador" {{ old('role', $usuario->role) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="Departamento" {{ old('role', $usuario->role) == 'Departamento' ? 'selected' : '' }}>Departamento</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar usuario</button>
        </div>
    </form>
</div>
@endsection
