@extends('layouts.brigadista')

@section('title', 'Acciones Brigadista')

@section('content')
<div class="container mt-5">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- DATOS DEL BRIGADISTA --}}
    <h5 class="mb-4" style="color:#6A1B4D; font-weight: bold;">DATOS DEL BRIGADISTA</h5>
    <div class="row">
        {{-- FOTO + DATOS PRINCIPALES --}}
        <div class="col-md-3 text-center">
            @if($brigadista->foto)
                <img src="{{ asset('storage/' . $brigadista->foto) }}"
                     alt="Foto del brigadista"
                     class="img-fluid rounded mb-3 shadow-sm"
                     style="max-width: 180px; height: 220px; object-fit: cover;">
            @else
                <img src="{{ asset('img/default-user.png') }}"
                     alt="Foto no disponible"
                     class="img-fluid rounded mb-3 shadow-sm"
                     style="max-width: 180px; height: 220px; object-fit: cover;">
            @endif

            <p><strong>Nombre:</strong><br> {{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</p>
            <p><strong>CURP:</strong><br> {{ $brigadista->curp }}</p>
            <p><strong>No. Boleta/Empleado:</strong><br> {{ $brigadista->numero_boleta }}</p>
        </div>

        {{-- PESTAÑAS CON INFORMACIÓN DETALLADA --}}
        <div class="col-md-9">
            {{-- Nav Tabs --}}
            <ul class="nav nav-tabs" id="brigadistaTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="generales-tab" data-bs-toggle="tab" data-bs-target="#generales" type="button" role="tab">GENERALES</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="domicilio-tab" data-bs-toggle="tab" data-bs-target="#domicilio" type="button" role="tab">DOMICILIO</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contacto-tab" data-bs-toggle="tab" data-bs-target="#contacto" type="button" role="tab">CONTACTO DE EMERGENCIA</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="registro-tab" data-bs-toggle="tab" data-bs-target="#registro" type="button" role="tab">REGISTRO DE BRIGADAS</button>
                </li>
            </ul>

            {{-- Contenido de las pestañas --}}
<div class="tab-content mt-3" id="brigadistaTabsContent">

    {{-- GENERALES --}}
    <div class="tab-pane fade show active" id="generales" role="tabpanel">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Programa Académico:</strong> {{ $brigadista->programa_academico }}</p>
                <p><strong>Unidad Académica:</strong> {{ $brigadista->unidad_academica }}</p>
                <p><strong>Especialidad:</strong> {{ $brigadista->especialidad }}</p>
                <p><strong>Situación académica:</strong> {{ $brigadista->situacion_academica }}</p>
                <p><strong>Institución SS:</strong> {{ $brigadista->institucion_ss }}</p>
                <p><strong>NSS:</strong> {{ $brigadista->nss }}</p>
                <p><strong>CLABE:</strong> {{ $brigadista->clabe ?? 'No registrada' }}</p>
                <p><strong>Banco:</strong> {{ $brigadista->banco ?? 'No registrado' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Teléfono:</strong> {{ $brigadista->telefono }}</p>
                <p><strong>Correo:</strong> {{ $brigadista->correo }}</p>
                <p><strong>Género:</strong> {{ $brigadista->genero }}</p>
                <p><strong>Edad:</strong> {{ $brigadista->edad }}</p>
            </div>
        </div>
    </div>

    {{-- DOMICILIO --}}
    <div class="tab-pane fade" id="domicilio" role="tabpanel">
        <div class="row">
            <div class="col-md-4">
                <p><strong>Calle:</strong> {{ $brigadista->calle }}</p>
                <p><strong>No. Interior:</strong> {{ $brigadista->no_int }}</p>
                <p><strong>No. Exterior:</strong> {{ $brigadista->no_ext }}</p>
                <p><strong>C.P.:</strong> {{ $brigadista->cp }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Colonia:</strong> {{ $brigadista->colonia }}</p>
                <p><strong>Municipio:</strong> {{ $brigadista->municipio }}</p>
                <p><strong>Estado:</strong> {{ $brigadista->estado }}</p>
            </div>
        </div>
    </div>

    {{-- CONTACTO DE EMERGENCIA --}}
    <div class="tab-pane fade" id="contacto" role="tabpanel">
        <form action="{{ route('brigadistas.actualizarContacto', $brigadista->id) }}" method="POST">
            @csrf
            <div class="row">
                {{-- Datos principales --}}
                <div class="col-md-4">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="contacto_nombre" class="form-control" value="{{ $brigadista->contacto_nombre }}">
                    </div>
                    <div class="mb-3">
                        <label>Parentesco</label>
                        <select name="contacto_parentesco" class="form-select">
                            <option value="">Selecciona</option>
                            <option value="PADRE" {{ $brigadista->contacto_parentesco=='PADRE'?'selected':'' }}>Padre</option>
                            <option value="MADRE" {{ $brigadista->contacto_parentesco=='MADRE'?'selected':'' }}>Madre</option>
                            <option value="HERMANO" {{ $brigadista->contacto_parentesco=='HERMANO'?'selected':'' }}>Hermano</option>
                            <option value="HERMANA" {{ $brigadista->contacto_parentesco=='HERMANA'?'selected':'' }}>Hermana</option>
                            <option value="AMIGO" {{ $brigadista->contacto_parentesco=='AMIGO'?'selected':'' }}>Amigo</option>
                            <option value="AMIGA" {{ $brigadista->contacto_parentesco=='AMIGA'?'selected':'' }}>Amiga</option>
                            <option value="OTRO" {{ $brigadista->contacto_parentesco=='OTRO'?'selected':'' }}>Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Teléfono</label>
                        <input type="text" name="telefono_contacto" class="form-control" value="{{ $brigadista->telefono_contacto }}">
                    </div>
                </div>

                {{-- Dirección de contacto --}}
                <div class="col-md-4">
                    <div class="mb-3">
                        <label>Calle</label>
                        <input type="text" name="calle_contacto" class="form-control" value="{{ $brigadista->calle_contacto }}">
                    </div>
                    <div class="mb-3">
                        <label>No. Interior</label>
                        <input type="text" name="no_int_contacto" class="form-control" value="{{ $brigadista->no_int_contacto }}">
                    </div>
                    <div class="mb-3">
                        <label>No. Exterior</label>
                        <input type="text" name="no_ext_contacto" class="form-control" value="{{ $brigadista->no_ext_contacto }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label>Colonia</label>
                        <input type="text" name="colonia_contacto" class="form-control" value="{{ $brigadista->colonia_contacto }}">
                    </div>
                    <div class="mb-3">
                        <label>Municipio</label>
                        <input type="text" name="municipio_contacto" class="form-control" value="{{ $brigadista->municipio_contacto }}">
                    </div>
                    <div class="mb-3">
                        <label>Estado</label>
                        <input type="text" name="estado_contacto" class="form-control" value="{{ $brigadista->estado_contacto }}">
                    </div>
                    <div class="mb-3">
                        <label>CP</label>
                        <input type="text" name="cp_contacto" class="form-control" value="{{ $brigadista->cp_contacto }}">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Guardar Contacto</button>
        </form>
    </div>

{{-- REGISTRO DE BRIGADAS --}}
<div class="tab-pane fade" id="registro" role="tabpanel">
    <form action="{{ route('brigadistas.actualizarRegistro', $brigadista->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- Columna 1 --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <h5>Registrarme en una Brigada</h5>
                    <label for="brigada_id" class="form-label">Selecciona una brigada:</label>
                    <select name="brigada_id" id="brigada_id" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($brigadas as $brigada)
                            <option value="{{ $brigada->id }}" 
                                {{ $brigadista->brigada_id == $brigada->id ? 'selected' : '' }}>
                                {{ $brigada->brigada }} - {{ $brigada->año }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Rol que deseas desempeñar</label>
                    <select name="rol_deseado" class="form-select">
                        <option value="">Selecciona rol</option>
                        <option value="BRIGADISTA" {{ $brigadista->rol_deseado=='BRIGADISTA'?'selected':'' }}>Brigadista</option>
                        <option value="COORDINADOR" {{ $brigadista->rol_deseado=='COORDINADOR'?'selected':'' }}>Coordinador</option>
                        <option value="SUPERVISOR" {{ $brigadista->rol_deseado=='SUPERVISOR'?'selected':'' }}>Supervisor</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Modalidad de participación</label>
                    <select name="modalidad" class="form-select">
                        <option value="">Selecciona modalidad</option>
                        <option value="VOLUNTARIADO" {{ $brigadista->modalidad=='VOLUNTARIADO'?'selected':'' }}>Voluntariado</option>
                        <option value="SERVICIO SOCIAL" {{ $brigadista->modalidad=='SERVICIO SOCIAL'?'selected':'' }}>Servicio Social</option>
                        <option value="HORAS ELECTIVAS" {{ $brigadista->modalidad=='HORAS ELECTIVAS'?'selected':'' }}>Horas Electivas</option>
                        <option value="RETRIBUCION SOCIAL" {{ $brigadista->modalidad=='RETRIBUCION SOCIAL'?'selected':'' }}>Retribución Social</option>
                        <option value="UNIDADES DE PROMOCION (U.P.)" {{ $brigadista->modalidad=='UNIDADES DE PROMOCION (U.P.)'?'selected':'' }}>Unidades de Promoción (U.P.)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Talla de playera</label>
                    <select name="talla" class="form-select">
                        <option value="">Selecciona talla</option>
                        <option value="S" {{ $brigadista->talla=='S'?'selected':'' }}>S</option>
                        <option value="M" {{ $brigadista->talla=='M'?'selected':'' }}>M</option>
                        <option value="L" {{ $brigadista->talla=='L'?'selected':'' }}>L</option>
                        <option value="XL" {{ $brigadista->talla=='XL'?'selected':'' }}>XL</option>
                        <option value="XXL" {{ $brigadista->talla=='XXL'?'selected':'' }}>XXL</option>
                    </select>
                </div>
            </div>

            {{-- Columna 2 --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label>¿Registrado en el SISS?</label>
                    <select name="siss" class="form-select">
                        <option value="">Selecciona</option>
                        <option value="SÍ" {{ $brigadista->siss=='SÍ'?'selected':'' }}>Sí</option>
                        <option value="NO" {{ $brigadista->siss=='NO'?'selected':'' }}>No</option>
                        <option value="NO APLICA" {{ $brigadista->siss=='NO APLICA'?'selected':'' }}>No aplica</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Participación en brigadas anteriores</label>
                    <select name="participacion_anterior" class="form-select">
                        <option value="">Selecciona</option>
                        <option value="1" {{ $brigadista->participacion_anterior=='1'?'selected':'' }}>1</option>
                        <option value="2" {{ $brigadista->participacion_anterior=='2'?'selected':'' }}>2</option>
                        <option value="3 O MÁS" {{ $brigadista->participacion_anterior=='3 O MÁS'?'selected':'' }}>3 o más</option>
                        <option value="NINGUNA" {{ $brigadista->participacion_anterior=='NINGUNA'?'selected':'' }}>Ninguna</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Municipio de última brigada</label>
                    <input type="text" name="municipio_anterior" class="form-control" value="{{ $brigadista->municipio_anterior }}">
                </div>
            </div>
        </div>

       {{-- LISTA DE DOCUMENTOS --}}
<div class="mt-4">
    <h5>Documentos</h5>
    @php
        $documentos = [
            'status' => 'Estatus de salud',
            'ficha' => 'Ficha de registro',
            'carta' => 'Carta compromiso'
        ];
    @endphp

    <ul class="list-group mb-3">
        @foreach($documentos as $key => $doc)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $doc }}</span>
                <div class="d-flex gap-2">
                    @if($key === 'status')
                        <a href="{{ route('brigadistas.statusSalud', $brigadista->id) }}" class="btn btn-warning btn-sm">
                            Generar
                        </a>
                    @endif
                    <a href="{{ route('brigadistas.ver'.ucfirst($key), $brigadista->id) }}" class="btn btn-info btn-sm" target="_blank">
                        Ver
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

    {{-- Botón único para firmar todos los documentos --}}
    <div class="text-end mb-3">
        <a href="{{ route('brigadistas.firma', ['brigadista' => $brigadista->id, 'documento' => 'todos']) }}"  
              class="btn btn-warning btn-sm"
              onclick="return checkStatus({{ $brigadista->status_completado ? 'true' : 'false' }})">
              Firmar todos los documentos
        </a>
    </div>
</div>

        {{-- Botón guardar registro --}}
        <button type="submit" class="btn btn-primary mt-3">Guardar Registro</button>
    </form>
    {{-- === HISTORIAL DE BRIGADAS === --}}
    <h5 class="mt-4">Historial de Brigadas</h5>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Brigada</th>
                <th>Año</th>
                <th>Rol</th>
                <th>Modalidad</th>
                <th>Validación</th>
                <th>Asignación</th>
                <th>Proyecto</th>
                <th>Estatus</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($brigadista->registros as $registro)
            <tr>
                <td>{{ $registro->brigada->brigada }}</td>
                <td>{{ $registro->brigada->año }}</td>
                <td>{{ $registro->rol_deseado }}</td>
                <td>{{ $registro->modalidad }}</td>

{{-- Validación --}}
<td>
    @php
        $status = $registro->status ?? 'pendiente';
    @endphp

    <input type="text" class="form-control
        @if($status === 'pendiente') bg-secondary text-white
        @elseif($status === 'validado') bg-success text-white
        @elseif($status === 'rechazado') bg-warning text-dark
        @elseif($status === 'cancelado') bg-danger text-white
        @endif
    " value="{{ ucfirst($status) }}" readonly>
</td>
@php
    $asignacion = $brigadista->asignaciones()->first();
@endphp

<td>
    <input type="text" class="form-control
        @if(optional($asignacion)->publicado) bg-success text-white
        @else bg-secondary text-white
        @endif
    " value="{{ optional($asignacion)->publicado ? $asignacion->municipio_asignado : '' }}" readonly>
</td>
<td>
    <input type="text" class="form-control
        @if(optional($asignacion)->publicado) bg-success text-white
        @else bg-secondary text-white
        @endif
    " value="{{ optional($asignacion)->publicado ? $asignacion->proyecto_asignado : '' }}" readonly>
</td>
                {{-- Estatus de la brigada --}}
                <td>
                    @if($registro->brigada->estado == 1)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-secondary">Inactiva</span>
                    @endif
                </td>

                {{-- Opciones --}}
                <td>
                    <form action="{{ route('registros.eliminar', $registro->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay registros de brigadas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

   {{-- Observaciones --}}
<div class="mb-3 mt-3">
    <label for="observaciones" class="form-label">Observaciones del departamento</label>
    <textarea class="form-control" id="observaciones" rows="3" readonly>{{ $brigadista->registros->last()->comentario ?? '' }}</textarea>
</div>

</div>
</div>
</div>
</div>


        {{-- Mensaje de PLANASSZITO --}}
        <div class="d-flex align-items-center p-4 rounded" style="background-color: #f4f0f5; border: 1px solid #6A1B4D;">
            <div class="me-4" style="flex-shrink: 0;">
                <img src="{{ asset('images/planasszito.png') }}" alt="PLANASSZITO" style="width:170px; height: auto;">
            </div>
            <div>
                <h5 style="color: #6A1B4D; font-weight: bold;">¡Hola, soy PLANASSZITO!</h5>
                <p style="color: #333; font-size: 0.95rem;">
                    Una vez que hayas sido validado, mándame mensaje a este número: <strong>55 2856 6679</strong><br>
                    Con los siguientes datos:<br>
                    • Tu número de folio<br>
                    • Nombre<br>
                    • Rol asignado
                </p>
            </div>
        </div>
    </div>


<script>
function checkStatus(statusCompletado) {
    if (!statusCompletado) {
        alert('Primero genera tu estatus de salud.');
        return false; // evita que se envíe al backend
    }
    return true;
}
</script>
@endsection
