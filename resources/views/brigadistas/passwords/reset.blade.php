@extends('layouts.login')

@section('content')
<div class="text-center mt-5">
    <h2 style="color:#6A1B4D; font-weight:bold;">Brigadas "PLANASSZE"</h2>
</div>

<div class="d-flex justify-content-center mt-4">
    <div class="card p-4" style="width: 400px;">
        <h3 class="text-center mb-4" style="color:#6A1B4D;">Restablecer Contraseña</h3>

        <form method="POST" action="{{ route('brigadistas.password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password">Nueva contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="background-color:#6A1B4D;">Restablecer contraseña</button>
        </form>
    </div>
</div>
@endsection
