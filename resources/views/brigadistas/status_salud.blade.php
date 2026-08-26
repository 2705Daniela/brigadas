@extends('layouts.brigadista')

@section('title', 'Status de Salud')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header text-white" style="background-color:#6A1B4D; font-weight:bold;">
            ESTATUS DE SALUD DEL ASPIRANTE A BRIGADA
        </div>
        <div class="card-body">

            <form action="{{ route('status.guardar', $brigadista->id) }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Parte 1: Enfermedades --}}
                    @php
                        $enfermedades = [
                            'cardiovascular' => 'Cardiovasculares',
                            'hipertension' => 'Hipertensión',
                            'diabetes' => 'Diabetes',
                            'epilepsia' => 'Epilepsia',
                            'trastornos_mentales' => 'Trastornos mentales',
                            'trastornos_visuales' => 'Trastornos visuales',
                            'ets' => 'Enfermedades de transmisión sexual (incluido VIH/SIDA)',
                            'hemorragia_interna' => 'Hemorragia interna',
                            'hepatitis' => 'Hepatitis',
                            'otra' => 'Otra (especifique)'
                        ];
                    @endphp

                    @foreach($enfermedades as $key => $label)
<div class="col-md-6 mb-3">
    <label class="form-label">{{ $label }}</label><br>
    <div class="form-check form-check-inline">
        <input class="form-check-input enfermedad-si" type="radio" name="{{ $key }}" value="Sí" id="{{ $key }}_si">
        <label class="form-check-label" for="{{ $key }}_si">Sí</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="{{ $key }}" value="No" id="{{ $key }}_no">
        <label class="form-check-label" for="{{ $key }}_no">No</label>
    </div>

    {{-- Textarea para ampliar respuesta --}}
    <textarea class="form-control mt-2 especificacion" name="{{ $key }}_detalle" placeholder="Amplíe su respuesta" style="display:none;"></textarea>

    {{-- Opciones adicionales para trastornos mentales --}}
    @if($key === 'trastornos_mentales')
    <div class="mt-2 opciones-trastornos" style="display:none;">
        <div class="form-check">
            <input class="form-check-input opcion-trastorno" type="checkbox" value="TDAH" id="tdah">
            <label class="form-check-label" for="tdah">TDAH</label>
        </div>
        <div class="form-check">
            <input class="form-check-input opcion-trastorno" type="checkbox" value="Esquizofrenia" id="esquizofrenia">
            <label class="form-check-label" for="esquizofrenia">Esquizofrenia</label>
        </div>
        <div class="form-check">
            <input class="form-check-input opcion-trastorno" type="checkbox" value="Autismo" id="autismo">
            <label class="form-check-label" for="autismo">Autismo</label>
        </div>
        <div class="form-check mt-1">
            <input class="form-check-input" type="checkbox" value="Otro" id="otro_trastorno">
            <label class="form-check-label" for="otro_trastorno">Otro</label>
            <input type="text" class="form-control mt-1" id="otro_trastorno_texto" placeholder="Especifique" style="display:none;">
        </div>
    </div>
    @endif
</div>
@endforeach

    @php
        $preguntas = [
          'tratamiento' => '¿Actualmente está bajo tratamiento médico?¿De qué tipo?',
            'medicamento_actual' => '¿Actualmente usa algún medicamento?¿Cuál?',
            'alergias' => '¿Es usted alérgico(a) a algún medicamento, sustancia o alimento?¿Cuál?',
            'operacion' => '¿Le han practicado alguna operación?',
            'embarazo' => '¿Está usted embarazada?¿Desde cuándo?',
            'grupo_sanguineo' => '¿Cuál es su grupo sanguíneo?',
            'informacion_adicional' => 'Si lo desea, agregue otra información'
        ];
    @endphp

    @foreach($preguntas as $key => $label)
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ $label }}</label><br>

        @if(!in_array($key, ['grupo_sanguineo', 'informacion_adicional']))
            <div class="form-check form-check-inline">
                <input class="form-check-input pregunta-si" type="radio" name="{{ $key }}" value="Sí" id="{{ $key }}_si">
                <label class="form-check-label" for="{{ $key }}_si">Sí</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="{{ $key }}" value="No" id="{{ $key }}_no">
                <label class="form-check-label" for="{{ $key }}_no">No</label>
            </div>

            <textarea class="form-control mt-2 especificacion" name="{{ $key }}_detalle" placeholder="Especifique" style="display:none;"></textarea>
        @else
            <input type="text" class="form-control" name="{{ $key }}">
        @endif
    </div>
    @endforeach
</div>

<button type="submit" class="btn btn-success">Guardar Status de Salud</button>

</div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Parte 1: Mostrar/ocultar textareas para enfermedades
    document.querySelectorAll('.enfermedad-si').forEach(radio => {
        radio.addEventListener('change', function() {
            const textarea = this.closest('.col-md-6').querySelector('.especificacion');
            if(this.value === 'Sí') {
                textarea.style.display = 'block';
                if(this.name === 'trastornos_mentales') {
                    document.querySelector('.opciones-trastornos').style.display = 'block';
                    actualizarTextareaTrastornos();
                }
            }
        });
    });
    document.querySelectorAll('.form-check-input[value="No"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const textarea = this.closest('.col-md-6').querySelector('.especificacion');
            if(textarea) {
                textarea.style.display = 'none';
                textarea.value = '';
                if(this.name === 'trastornos_mentales') {
                    document.querySelector('.opciones-trastornos').style.display = 'none';
                    document.querySelectorAll('.opcion-trastorno').forEach(c => c.checked = false);
                    document.getElementById('otro_trastorno_texto').style.display = 'none';
                    document.getElementById('otro_trastorno_texto').value = '';
                }
            }
        });
    });

    // Parte 1: Opciones adicionales para trastornos mentales
    const checkboxes = document.querySelectorAll('.opcion-trastorno');
    const otroCheck = document.getElementById('otro_trastorno');
    const otroInput = document.getElementById('otro_trastorno_texto');
    const textarea = document.querySelector('textarea[name="trastornos_mentales_detalle"]');

    checkboxes.forEach(c => {
        c.addEventListener('change', actualizarTextareaTrastornos);
    });

    otroInput.addEventListener('input', actualizarTextareaTrastornos);

    otroCheck.addEventListener('change', () => {
        otroInput.style.display = otroCheck.checked ? 'block' : 'none';
        actualizarTextareaTrastornos();
    });

    function actualizarTextareaTrastornos() {
        let seleccion = [];
        checkboxes.forEach(c => {
            if(c.checked && c.value !== 'Otro') seleccion.push(c.value);
        });
        if(otroCheck.checked && otroInput.value.trim() !== '') seleccion.push(otroInput.value.trim());
        textarea.value = seleccion.join(', ');
    }

    // Parte 2: Mostrar/ocultar textareas para preguntas de la segunda sección
    document.querySelectorAll('.pregunta-si').forEach(radio => {
        radio.addEventListener('change', function() {
            const textarea = this.closest('.col-md-6').querySelector('.especificacion');
            if(this.value === 'Sí') {
                textarea.style.display = 'block';
            }
        });
    });
});
</script>


@endsection
