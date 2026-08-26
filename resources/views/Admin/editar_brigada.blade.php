@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Editar Brigada</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    
    <form action="{{ route('admin.editar_brigada.update', $brigada->id) }}" method="POST">
    @csrf
    @method('PUT')

        {{-- Brigada --}}
        <div class="mb-3">
            <label for="brigada" class="form-label">Brigada</label>
            <select name="brigada" id="brigada" class="form-control" required>
                <option value="">Selecciona...</option>
                <option value="Primavera" {{ $brigada->brigada == 'Primavera' ? 'selected' : '' }}>Primavera</option>
                <option value="Verano" {{ $brigada->brigada == 'Verano' ? 'selected' : '' }}>Verano</option>
                <option value="Otoño" {{ $brigada->brigada == 'Otoño' ? 'selected' : '' }}>Otoño</option>
                <option value="Emergente" {{ $brigada->brigada == 'Emergente' ? 'selected' : '' }}>Emergente</option>
                <option value="Extraordinaria" {{ $brigada->brigada == 'Extraordinaria' ? 'selected' : '' }}>Extraordinaria</option>
                <option value="Otra" {{ $brigada->brigada == 'Otra' ? 'selected' : '' }}>Otra</option>
            </select>
        </div>

        {{-- Año --}}
        <div class="mb-3">
            <label for="año" class="form-label">Año</label>
            <input type="number" name="año" id="año" class="form-control" required value="{{ $brigada->año }}">
        </div>

        {{-- Periodo Inicio --}}
        <div class="mb-3">
            <label for="periodo_inicio" class="form-label">Periodo Inicio</label>
            <input type="date" name="periodo_inicio" id="periodo_inicio" class="form-control" required value="{{ $brigada->periodo_inicio }}">
        </div>

        {{-- Periodo Fin --}}
        <div class="mb-3">
            <label for="periodo_fin" class="form-label">Periodo Fin</label>
            <input type="date" name="periodo_fin" id="periodo_fin" class="form-control" required value="{{ $brigada->periodo_fin }}">
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label class="form-label d-block">Estado</label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label class="switch">
                    <input type="checkbox" id="estado" name="estado" value="1" {{ $brigada->estado ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
                <span id="estado-label" style="font-weight: bold; font-size: 1.1rem;">
                    {{ $brigada->estado ? 'Activa' : 'Inactiva' }}
                </span>
            </div>
        </div>

        {{-- Botones --}}
        <button type="submit" class="btn btn-success">Actualizar Brigada</button>
        <a href="{{ route('admin.gestionar_brigada') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

{{-- Switch CSS --}}
<style>
.switch {
    position: relative;
    display: inline-block;
    width: 70px;  
    height: 38px;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 38px;
}
.slider::before {
    position: absolute;
    content: "";
    height: 32px;
    width: 32px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}
input:checked + .slider {
    background-color: #8B0000;
}
input:checked + .slider::before {
    transform: translateX(32px);
}
</style>

{{-- Script para mostrar Activa/Inactiva --}}
<script>
const estadoCheckbox = document.getElementById('estado');
const estadoLabel = document.getElementById('estado-label');

estadoCheckbox.addEventListener('change', () => {
    estadoLabel.textContent = estadoCheckbox.checked ? 'Activa' : 'Inactiva';
});
</script>
@endsection
