@extends('layouts.login')

@section('content')
<div class="text-center mt-5">
    <h2 style="color:#6A1B4D; font-weight:bold;">Sistema de Gestión de Brigadas "PLANASSZE"</h2>
</div>

<div class="d-flex justify-content-center align-items-center mt-4" style="min-height: 65vh;">
    <div class="card shadow-sm p-4" style="width: 400px;">
        <h3 class="text-center mb-4" style="color:#6A1B4D;">Iniciar Sesión</h3>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="otp" class="form-label">Código </label>
        <input type="text" name="one_time_password" id="otp" class="form-control" placeholder="123456" required>
    </div>

    <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary" style="background-color:#6A1B4D; border:none;">Ingresar</button>
    </div>
</form>
    </div>
</div>
@endsection
