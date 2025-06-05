<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect()->route('login')
                ->with('status', 'No se pudo verificar el correo electrónico. Usuario no encontrado.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false))
                ->with('success', '¡Su correo electrónico ya había sido verificado anteriormente!');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            // Si el usuario está autenticado, usar su sesión
            if ($request->user() && $request->user()->id === $user->id) {
                return redirect()->intended(route('dashboard', absolute: false))
                    ->with('success', '¡Su correo electrónico ha sido verificado correctamente!');
            }

            // Si el usuario no está autenticado, enviarlo al login
            return redirect()->route('login')
                ->with('success', '¡Su correo electrónico ha sido verificado correctamente! Ahora puede iniciar sesión.');
        }

        return redirect()->route('login')
            ->with('status', 'No se pudo verificar el correo electrónico. Por favor, inténtelo de nuevo.');
    }
}
