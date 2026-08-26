@extends('layouts.login')

@section('content')
<div class="text-center mt-5">
    <h2 style="color:#6A1B4D; font-weight:bold;">Brigadas "PLANASSZE"</h2>
</div>

<div class="d-flex justify-content-center align-items-center mt-4" style="min-height: 65vh;">
    <div class="card shadow-sm p-4" style="width: 400px;">
        <h3 class="text-center mb-4" style="color:#6A1B4D;">Iniciar Sesión</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('brigadista.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo') }}" required autofocus>
                @error('correo')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="mb-3">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('password')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="mb-3">
                <label for="captcha">Captcha</label>
                <div class="d-flex align-items-center">
                    <img src="{{ captcha_src('flat') }}" id="captcha-img" alt="captcha" style="height:50px;">
                    <button type="button" class="btn btn-sm btn-secondary ms-2" id="reload">↻</button>
                </div>
                <input type="text" name="captcha" id="captcha" class="form-control mt-2" placeholder="Ingresa el texto">
                @error('captcha')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn-primary w-100" style="background-color:#6A1B4D; border:none;">Ingresar</button>
        </form>

        <!-- Botón de recuperación automática -->
        <form method="POST" action="{{ route('brigadistas.password.auto') }}" class="mt-3 text-center" id="forgot-password-form">
            @csrf
            <input type="hidden" name="correo" id="forgot-correo">
            <button type="submit" class="btn btn-link" style="color:#6A1B4D;">¿Olvidaste tu contraseña?</button>
        </form>

        <!-- Mensaje de éxito -->
        @if(session('status'))
            <div class="alert alert-success mt-2">{{ session('status') }}</div>
        @endif

        <!-- Mensaje de error -->
        @if($errors->has('correo'))
            <div class="alert alert-danger mt-2">{{ $errors->first('correo') }}</div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$('#reload').click(function () {
    let d = new Date();
    $('#captcha-img').attr('src', '/captcha/flat?' + d.getTime());
});

// Pasar el correo del login al formulario de recuperación automática
$('#forgot-password-form').submit(function() {
    $('#forgot-correo').val($('#correo').val());
});
</script>
@endsection
