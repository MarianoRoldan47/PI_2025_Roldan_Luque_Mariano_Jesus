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
    <h4 class="mb-4 text-center text-white">{{ __('¿Olvidaste tu contraseña?') }}</h4>

    <div class="mb-4 text-center text-white-50 small">
        {{ __('No hay problema. Solo indícanos tu correo electrónico y te enviaremos un enlace para que puedas crear una nueva.') }}
    </div>


    @if (session('status'))
        <div class="mb-4 alert alert-success d-flex align-items-center small" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink">

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


        <button type="submit"
                class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center"
                style="background-color: #22a7e1; border: none;">
            <i class="fas fa-paper-plane me-2"></i>
            {{ __('Enviar enlace de recuperación') }}
        </button>


        <div class="mt-4 text-center">
            <a href="{{ route('login') }}"
               class="text-primary text-decoration-none small"
               wire:navigate>
                <i class="fas fa-arrow-left me-1"></i>
                {{ __('Volver al inicio de sesión') }}
            </a>
        </div>
    </form>
</div>
