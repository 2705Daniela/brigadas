@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Autenticación de dos factores para {{ $usuario->name }}</h2>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Mostrar QR y clave si se generó --}}
    @if(!empty($qrCodeUrl) && !empty($secretKey))
        <p>Escanea este QR con Google Authenticator o Authy:</p>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}" alt="QR Code">
        <p class="mt-2">Clave secreta: <strong>{{ $secretKey }}</strong></p>
    @endif

    {{-- Botones para habilitar/deshabilitar 2FA --}}
 {{-- Botones para habilitar/deshabilitar 2FA --}}
<div class="mt-3">
    @if(!$usuario->google2fa_secret)
        <form action="{{ route('admin.usuarios.enable2fa', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ $usuario->id }}">
            {{-- Aquí deberías pedir el código OTP si lo vas a verificar --}}
            <button type="submit" class="btn btn-primary">Habilitar 2FA</button>
        </form>
    @else
        <form action="{{ route('admin.usuarios.disable2fa', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ $usuario->id }}">
            <button type="submit" class="btn btn-danger">Deshabilitar 2FA</button>
        </form>
    @endif
</div>
    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary mt-3">Regresar a lista de usuarios</a>
</div>
@endsection
