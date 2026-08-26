<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Brigadista;
use App\Notifications\BrigadistaResetPasswordNotification;

class BrigadistaAuthController extends Controller
{
    // Método automático de recuperación
    public function sendResetLinkAuto(Request $request)
{
    $request->validate(['correo' => 'required|email']);

    $brigadista = Brigadista::where('correo', $request->correo)->first();

    if (!$brigadista) {
        return back()->withErrors(['correo' => 'No se encontró la cuenta de brigadista.']);
    }

    // Genera el token
    $token = Password::broker('brigadistas')->createToken($brigadista);

    // Envía la notificación manualmente
    $brigadista->notify(new BrigadistaResetPasswordNotification($token));

    return back()->with('status', "Se ha enviado un correo de recuperación de contraseña a la cuenta {$brigadista->correo}");
}
    public function showLinkRequestForm()
{
    return view('brigadistas.passwords.reset', ['token' => $token]);
}

public function showResetForm($token)
{
    return view('brigadistas.passwords.reset', ['token' => $token]);
}

public function reset(Request $request)
{
    $request->validate([
        'correo' => 'required|email',
        'password' => 'required|confirmed|min:6',
        'token' => 'required'
    ]);

    $status = Password::broker('brigadistas')->reset(
        $request->only('correo', 'password', 'password_confirmation', 'token'),
        function ($brigadista, $password) {
            $brigadista->password = bcrypt($password);
            $brigadista->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('brigadista.login')->with('status', 'Contraseña restablecida correctamente')
        : back()->withErrors(['correo' => __($status)]);
}

}
