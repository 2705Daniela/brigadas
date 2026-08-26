@extends('layouts.admin')

@section('content')
<div class="container mt-5">

    <h2>Lista de Supervisores</h2>

    @if ($supervisores->isEmpty())

        <div class="alert alert-warning text-center">
            No hay supervisores registrados.
        </div>

    @else

        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>CURP</th>
                        <th>Correo</th>
                        <th>Número de Teléfono</th>
                        <th>CLABE</th>
                        <th>Banco</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($supervisores as $index => $supervisor)

                        <tr>

                            <td class="text-center">
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $supervisor->nombre }}
                                {{ $supervisor->apellido_paterno }}
                                {{ $supervisor->apellido_materno }}
                            </td>

                            <td>
                                {{ $supervisor->curp }}
                            </td>

                            <td>
                                {{ $supervisor->correo }}
                            </td>

                            <td>
                                {{ $supervisor->telefono }}
                            </td>

                            <td>
                                {{ $supervisor->clabe }}
                            </td>

                            <td>
                                {{ $supervisor->banco }}
                            </td>

                            <td class="text-center">

                                <a href="{{ route('admin.supervisores.editar', $supervisor->id) }}"
                                   class="btn btn-link text-warning text-decoration-none">
                                    Editar
                                </a>

                                <form action="{{ route('admin.supervisores.eliminar', $supervisor->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este supervisor?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-link text-danger text-decoration-none">
                                        Eliminar
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>
@endsection