@extends('layouts.login')

@section('content')
<div class="text-center mt-5">
    <h2 style="color:#6A1B4D; font-weight:bold;">Brigadas "PLANASSZE"</h2>
</div>

<div class="d-flex justify-content-center mt-4">
    <div class="card p-4" style="width: 400px;">
        <h3 class="text-center mb-4" style="color:#6A1B4D;">Recuperar Contraseña</h3>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->has('correo'))
            <div class="alert alert-danger">{{ $errors->first('correo') }}</div>
        @endif

        <form method="POST" action="{{ route('brigadistas.password.auto') }}">
            @csrf
            <div class="mb-3">
                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" style="background-color:#6A1B4D;">Enviar enlace de recuperación</button>
        </form>
    </div>
</div>
@endsection
