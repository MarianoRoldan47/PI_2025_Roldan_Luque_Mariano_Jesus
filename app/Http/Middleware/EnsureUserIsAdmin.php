<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user() || Auth::user()->rol !== 'Administrador') {
            return redirect()->route('dashboard')->with([
                'status' => 'No tienes permisos para realizar esta acción.',
                'status-type' => 'danger'
            ]);
        }

        return $next($request);
    }
}
