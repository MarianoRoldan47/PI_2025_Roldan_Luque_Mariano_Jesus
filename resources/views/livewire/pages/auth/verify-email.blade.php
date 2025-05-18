<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <h4 class="text-white text-center mb-4">{{ __('Verifica tu correo electrónico') }}</h4>

    <div class="mb-4 text-white-50 small text-center">
        {{ __('¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que acabamos de enviarte? Si no recibiste el correo, con gusto te enviaremos otro.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success d-flex align-items-center small mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>
                {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico proporcionada durante el registro.') }}
            </div>
        </div>
    @endif

    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
        <button wire:click="sendVerification" class="btn btn-primary d-flex align-items-center"
            style="background-color: #22a7e1; border: none;">
            <i class="fas fa-envelope me-2"></i>
            {{ __('Reenviar correo de verificación') }}
        </button>

        <button wire:click="logout" type="submit" class="btn btn-link text-decoration-none text-white-50 small">
            <i class="fas fa-sign-out-alt me-2"></i>
            {{ __('Cerrar sesión') }}
        </button>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('dashboard') }}" class="text-primary text-decoration-none small" wire:navigate>
            <i class="fas fa-arrow-left me-1"></i>
            {{ __('Volver al inicio') }}
        </a>
    </div>
</div>
