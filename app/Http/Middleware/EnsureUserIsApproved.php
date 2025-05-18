<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->rol === 'Administrador') {
            return $next($request);
        }
        if (!Auth::user()->is_approved) {
            Auth::logout();

            return redirect()->route('login')
                ->with('status', 'Tu cuenta está pendiente de aprobación por un administrador.')
                ->with('status-type', 'warning');
        }

        return $next($request);
    }
}
