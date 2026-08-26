@extends('layouts.brigadista')

@section('title', 'Firma de Documento')

@section('content')
<div class="container mt-5">
    <div class="card shadow" style="border: 1px solid #6A1B4D;">
        <div class="card-header text-white" style="background-color: #6A1B4D; font-weight: bold;">
            FIRMA DE DOCUMENTO
        </div>
        <div class="card-body">
            <p>Nombre: {{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</p>
            <p>Documento: Ficha de registro, Carta Compromiso y Estatus de salud </p>

            {{-- Mostrar errores --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mensajes de sesión --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="formFirma" action="{{ route('brigadistas.firmarPDF', ['brigadista' => $brigadista->id, 'documento' => $documento]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label>Seleccione el método de firma</label>
                    <select name="tipo_firma" id="tipoFirma" class="form-select" required>
                        <option value="">-- Seleccione --</option>
                       <option value="efirma">Firmar con e.Firma</option>
                       <option value="curp">Firmar con CURP</option>

                    </select>
                </div>

                {{-- Campos e.Firma --}}
                <div id="efirmaFields" style="display: none;">
                    <div class="mb-3">
                        <label>Archivo .cer</label>
                        <input type="file" name="cer" class="form-control" accept=".cer">
                    </div>
                    <div class="mb-3">
                        <label>Archivo .key</label>
                        <input type="file" name="key" class="form-control" accept=".key">
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                    </div>
                </div>

                {{-- Campos CURP --}}
                <div id="curpFields" style="display: none;">
                    <div class="mb-3">
                        <label>Ingrese su CURP</label>
                        <input type="text" name="firma_curp" class="form-control" value="{{ old('firma_curp') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-ipn mt-2">Firmar documentos</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipoFirma');
    const efirmaFields = document.getElementById('efirmaFields');
    const curpFields = document.getElementById('curpFields');

    function toggleFields() {
        const tipo = tipoSelect.value;
        efirmaFields.style.display = tipo === 'efirma' ? 'block' : 'none';
        curpFields.style.display = tipo === 'curp' ? 'block' : 'none';
    }

    tipoSelect.addEventListener('change', toggleFields);
    toggleFields(); // Para mantener selección si hay errores
});
</script>
@endsection
