@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h4>Información de Brigadista</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card p-3 mb-3">
        <div class="row">
            {{-- FOTO + Datos básicos --}}
            <div class="col-md-3 text-center">
                @if($brigadista->foto)
                    <img src="{{ asset('storage/' . $brigadista->foto) }}" 
                         alt="Foto del brigadista" class="img-fluid rounded mb-3" 
                         style="max-width: 180px; height: 220px; object-fit: cover;">
                @else
                    <p class="text-muted">No hay foto cargada</p>
                @endif

                <p><strong>Nombre:</strong> {{ $brigadista->nombre }} {{ $brigadista->apellido_paterno }} {{ $brigadista->apellido_materno }}</p>
                <p><strong>CURP:</strong> {{ $brigadista->curp }}</p>
                <p><strong>No. Boleta/Empleado:</strong> {{ $brigadista->numero_boleta }}</p>
            </div>

            {{-- DATOS DETALLADOS EN PESTAÑAS --}}
            <div class="col-md-9">
                <ul class="nav nav-tabs" id="brigadistaTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Información General</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="domicilio-tab" data-bs-toggle="tab" data-bs-target="#domicilio" type="button" role="tab">Domicilio</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contacto-tab" data-bs-toggle="tab" data-bs-target="#contacto" type="button" role="tab">Contacto de Emergencia</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="registro-tab" data-bs-toggle="tab" data-bs-target="#registro" type="button" role="tab">Registro a Brigadas</button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="brigadistaTabsContent">
                    {{-- Información General --}}
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Programa Académico:</strong> {{ $brigadista->programa_academico }}</p>
                                <p><strong>Unidad Académica:</strong> {{ $brigadista->unidad_academica }}</p>
                                <p><strong>Especialidad:</strong> {{ $brigadista->especialidad }}</p>
                                <p><strong>Situación Académica:</strong> {{ $brigadista->situacion_academica }}</p>
                                <p><strong>Institución SS:</strong> {{ $brigadista->institucion_ss }}</p>
                                <p><strong>NSS:</strong> {{ $brigadista->nss }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Teléfono:</strong> {{ $brigadista->telefono }}</p>
                                <p><strong>Correo:</strong> {{ $brigadista->correo }}</p>
                                <p><strong>Género:</strong> {{ $brigadista->genero }}</p>
                                <p><strong>Edad:</strong> {{ $brigadista->edad }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Domicilio --}}
                    <div class="tab-pane fade" id="domicilio" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Calle:</strong> {{ $brigadista->calle }}</p>
                                <p><strong>No. Int:</strong> {{ $brigadista->no_int }}</p>
                                <p><strong>No. Ext:</strong> {{ $brigadista->no_ext }}</p>
                                <p><strong>CP:</strong> {{ $brigadista->cp }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Colonia:</strong> {{ $brigadista->colonia }}</p>
                                <p><strong>Municipio:</strong> {{ $brigadista->municipio }}</p>
                                <p><strong>Estado:</strong> {{ $brigadista->estado }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Banco:</strong> {{ $brigadista->banco ?? 'No registrado' }}</p>
                                <p><strong>CLABE:</strong> {{ $brigadista->clabe ?? 'No registrada' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Contacto de Emergencia --}}
                    <div class="tab-pane fade" id="contacto" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Nombre:</strong> {{ $brigadista->contacto_nombre }}</p>
                                <p><strong>Parentesco:</strong> {{ $brigadista->contacto_parentesco }}</p>
                                <p><strong>Teléfono:</strong> {{ $brigadista->telefono_contacto }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Calle:</strong> {{ $brigadista->calle_contacto }}</p>
                                <p><strong>No. Int:</strong> {{ $brigadista->no_int_contacto }}</p>
                                <p><strong>No. Ext:</strong> {{ $brigadista->no_ext_contacto }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Colonia:</strong> {{ $brigadista->colonia_contacto }}</p>
                                <p><strong>Municipio:</strong> {{ $brigadista->municipio_contacto }}</p>
                                <p><strong>Estado:</strong> {{ $brigadista->estado_contacto }}</p>
                                <p><strong>CP:</strong> {{ $brigadista->cp_contacto }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Registro a Brigadas --}}
<div class="tab-pane fade" id="registro" role="tabpanel">
   @php
    $registro = $brigadista->registros
        ->first(function ($registro) {
            return $registro->brigada && $registro->brigada->estado == 1;
        });
@endphp

    @if($registro)
        <div class="row">
            <div class="col-md-6">
                <p><strong>Brigada:</strong> {{ optional($registro->brigada)->brigada }} - {{ optional($registro->brigada)->año }}</p>
                <p><strong>Rol que desea desempeñar:</strong> {{ $registro->rol_deseado }}</p>
                <p><strong>Modalidad:</strong> {{ $registro->modalidad }}</p>
                <p><strong>Talla de playera:</strong> {{ $registro->talla }}</p>
                <p><strong>Registro SISS:</strong> {{ $registro->siss }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Participación en brigadas anteriores:</strong> {{ $registro->participacion_anterior }}</p>
                <p><strong>Municipio de última brigada:</strong> {{ $registro->municipio_anterior }}</p>
            </div>
        </div>
    @else
        <p class="text-muted">El brigadista aún no tiene registro en ninguna brigada.</p>
    @endif

                                <h5 class="mt-3">Documentos del brigadista</h5>
                                <ul class="list-group mb-3">
                                    @foreach(['status' => 'Estatus de Salud', 'ficha' => 'Ficha de Registro', 'carta' => 'Carta Compromiso'] as $key => $doc)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $doc }}
                                            <a href="{{ route('brigadistas.ver'.ucfirst($key), $brigadista->id) }}" class="btn btn-info btn-sm" target="_blank">Ver</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
</div>
               {{-- FIRMA DEL BRIGADISTA --}}
<h5 class="mt-4">Firma del Brigadista</h5>
@if($brigadista->firma)
    <p><strong>Tipo de firma:</strong> {{ $brigadista->tipo_firma }}</p>
    <p style="font-family: cursive; font-size: 12px; white-space: pre-wrap; word-wrap: break-word; word-break: break-all;">
        {{ $brigadista->firma }}
    </p>
@else
    <p class="text-muted">El brigadista no ha generado su firma.</p>
@endif

                {{-- BOTONES DEL ADMINISTRADOR --}}
                <div class="mt-3 d-flex gap-2">
                    <form action="{{ route('admin.brigadistas.validar.submit', $registro->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Validar Registro</button>
                    </form>

                    <form action="{{ route('admin.brigadistas.rechazar', $registro->id) }}" method="POST">
                        @csrf
                        <textarea name="observaciones" class="form-control mb-2" placeholder="Indica qué debe corregir..." required></textarea>
                        <button type="submit" class="btn btn-warning">Rechazar Registro</button>
                    </form>

                    <form action="{{ route('admin.brigadistas.cancelar', $registro->id) }}" method="POST">
                        @csrf
                        <textarea name="observaciones" class="form-control mb-2" placeholder="Explica el motivo..." required></textarea>
                        <button type="submit" class="btn btn-danger">Cancelar Registro</button>
                    </form>
</div>
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

            </div>
        </div>
    </div>
</div>
@endsection
