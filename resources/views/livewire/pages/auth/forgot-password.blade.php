<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <h4 class="text-white text-center mb-4">{{ __('¿Olvidaste tu contraseña?') }}</h4>

    <div class="mb-4 text-white-50 small text-center">
        {{ __('No hay problema. Solo indícanos tu correo electrónico y te enviaremos un enlace para que puedas crear una nueva.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center small mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink">
        <!-- Email Address -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-primary">
                    <i class="fas fa-envelope"></i>
                </span>
                <input wire:model="email"
                       type="email"
                       id="email"
                       class="form-control form-control-lg bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                       required
                       autofocus
                       placeholder="{{ __('Correo electrónico') }}">
            </div>
            @error('email')
                <div class="invalid-feedback d-block small">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center"
                style="background-color: #22a7e1; border: none;">
            <i class="fas fa-paper-plane me-2"></i>
            {{ __('Enviar enlace de recuperación') }}
        </button>

        <!-- Back to Login -->
        <div class="text-center mt-4">
            <a href="{{ route('login') }}"
               class="text-primary text-decoration-none small"
               wire:navigate>
                <i class="fas fa-arrow-left me-1"></i>
                {{ __('Volver al inicio de sesión') }}
            </a>
        </div>
    </form>
</div>
