@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Crear Brigada</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.crear_brigada.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="brigada" class="form-label">Brigada</label>
            <select name="brigada" id="brigada" class="form-control" required>
                <option value="">Selecciona...</option>
                <option value="Primavera">Primavera</option>
                <option value="Verano">Verano</option>
                <option value="Otoño">Otoño</option>
                <option value="Emergente">Emergente</option>
                <option value="Extraordinaria">Extraordinaria</option>
                <option value="Otra">Otra </option>
            </select>
        </div>

        <div class="mb-3">
            <label for="año" class="form-label">Año</label>
            <input type="number" name="año" id="año" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="periodo_inicio" class="form-label">Periodo Inicio</label>
            <input type="date" name="periodo_inicio" id="periodo_inicio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="periodo_fin" class="form-label">Periodo Fin</label>
            <input type="date" name="periodo_fin" id="periodo_fin" class="form-control" required>
        </div>

<div class="mb-3">
    <label class="form-label d-block">Estado</label>
    <div style="display: flex; align-items: center; gap: 10px;">
        <label class="switch">
            <input type="checkbox" id="estado" name="estado" value="1" checked>
            <span class="slider"></span>
        </label>
        <span id="estado-label" style="font-weight: bold; font-size: 1.1rem;">Activa</span>
    </div>
</div>

<style>
/* Contenedor del switch */
.switch {
    position: relative;
    display: inline-block;
    width: 70px;  /* más grande */
    height: 38px; /* más grande */
}

/* Oculta checkbox real */
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

/* Slider */
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

/* Bola */
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

/* Estado activo */
input:checked + .slider {
    background-color: #8B0000; /* guinda */
}

input:checked + .slider::before {
    transform: translateX(32px);
}
</style>

<script>
const estadoCheckbox = document.getElementById('estado');
const estadoLabel = document.getElementById('estado-label');

estadoCheckbox.addEventListener('change', () => {
    estadoLabel.textContent = estadoCheckbox.checked ? 'Activa' : 'Inactiva';
});
</script>


        <button type="submit" class="btn btn-success">Guardar Brigada</button>
        <a href="{{ route('admin.gestionar_brigada') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
