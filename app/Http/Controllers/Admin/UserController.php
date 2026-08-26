<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FAQRCode\Google2FA; 
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Mostrar lista de usuarios
    public function index()
    {
        $usuarios = User::orderBy('name')->get();
        return view('admin.listas_usuarios', compact('usuarios'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $roles = ['Administrador', 'Departamento'];
        return view('admin.crear', compact('roles'));
    }

    // Guardar nuevo usuario con 2FA
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:Administrador,Departamento',
        ]);

        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'google2fa_secret' => $secretKey,
        ]);

        Bitacora::create([
    'usuario_id' => Auth::id(),
    'brigadista_id' => null,
    'accion' => 'Creó usuario',
    'observaciones' => 'Usuario creado: ' .
        $user->name . ' | Correo: ' .
        $user->email . ' | Rol: ' .
        $user->role,
]);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'PLANASSZE',
            $user->email,
            $secretKey
        );

        return view('admin.crear', [
            'roles' => ['Administrador', 'Departamento'],
            'qrCodeUrl' => $qrCodeUrl,
            'secretKey' => $secretKey,
        ])->with('success', 'Usuario creado correctamente. Escanea el código QR para habilitar 2FA.');
    }

    // Mostrar formulario de edición
    public function edit(User $usuario)
    {
        $roles = ['Administrador', 'Departamento'];
        return view('admin.editar', compact('usuario', 'roles'));
    }

    // Actualizar usuario
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:Administrador,Departamento',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->role = $request->role;

        if ($request->password) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        Bitacora::create([
    'usuario_id' => Auth::id(),
    'brigadista_id' => null,
    'accion' => 'Editó usuario',
    'observaciones' => 'Usuario modificado: ' .
        $usuario->name . ' | Correo: ' .
        $usuario->email . ' | Rol: ' .
        $usuario->role,
]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function destroy(User $usuario)
{
    $nombre = $usuario->name;
    $email = $usuario->email;
    $rol = $usuario->role;

    $usuario->delete();

    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => null,
        'accion' => 'Eliminó usuario',
        'observaciones' => 'Usuario eliminado: ' .
            $nombre . ' | Correo: ' .
            $email . ' | Rol: ' .
            $rol,
    ]);

    return redirect()
        ->route('admin.usuarios.index')
        ->with('success', 'Usuario eliminado correctamente.');
}

public function show2FA(User $usuario)
{
    return view('admin.2fa', compact('usuario'));
}
public function enable2FA(User $usuario)
{
    $google2fa = new Google2FA();
    $secretKey = $google2fa->generateSecretKey();

    $usuario->google2fa_secret = $secretKey;
    $usuario->save();

    Bitacora::create([
    'usuario_id' => Auth::id(),
    'brigadista_id' => null,
    'accion' => 'Habilitó 2FA',
    'observaciones' => 'Se generó una nueva configuración 2FA para el usuario: ' .
        $usuario->name . ' | Correo: ' .
        $usuario->email,
]);

    $qrCodeUrl = $google2fa->getQRCodeUrl('PLANASSZE', $usuario->email, $secretKey);

    // Mostrar la vista 2FA directamente
    return view('admin.2fa', compact('usuario', 'qrCodeUrl', 'secretKey'));
}

public function disable2FA(User $usuario)
{
    $usuario->google2fa_secret = null;
    $usuario->save();

    Bitacora::create([
    'usuario_id' => Auth::id(),
    'brigadista_id' => null,
    'accion' => 'Habilitó 2FA',
    'observaciones' => 'Se generó una nueva configuración 2FA para el usuario: ' .
        $usuario->name . ' | Correo: ' .
        $usuario->email,
]);

    return redirect()->route('admin.usuarios.2fa.show', $usuario->id)
                 ->with('success', 'Autenticación de dos factores deshabilitada.');
}
}
