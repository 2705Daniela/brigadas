<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $userRole = auth()->user()->role;

        // Administrador tiene acceso completo
        if ($userRole === 'Administrador') {
            return $next($request);
        }

        // Los demás usuarios deben tener el rol solicitado
        if ($userRole !== $role) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}