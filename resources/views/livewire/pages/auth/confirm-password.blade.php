<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h4 class="text-white text-center mb-4">{{ __('Confirmar Contraseña') }}</h4>

    <div class="mb-4 text-white-50 small text-center">
        {{ __('Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.') }}
    </div>

    <form wire:submit="confirmPassword">
        <!-- Password -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-primary">
                    <i class="fas fa-lock"></i>
                </span>
                <input wire:model="password"
                       type="password"
                       id="password"
                       class="form-control form-control-lg bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                       required
                       autofocus
                       autocomplete="current-password"
                       placeholder="{{ __('Contraseña') }}">
            </div>
            @error('password')
                <div class="invalid-feedback d-block small">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center"
                style="background-color: #22a7e1; border: none;">
            <i class="fas fa-check-circle me-2"></i>
            {{ __('Confirmar') }}
        </button>

        <!-- Back Link -->
        <div class="text-center mt-4">
            <a href="{{ route('dashboard') }}"
               class="text-primary text-decoration-none small"
               wire:navigate>
                <i class="fas fa-arrow-left me-1"></i>
                {{ __('Volver al inicio') }}
            </a>
        </div>
    </form>
</div>
