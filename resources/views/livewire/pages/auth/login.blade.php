<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        try {
            $this->form->authenticate();

            Session::regenerate();

            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } catch (\Exception $e) {
            // Añadir mensaje de error en sesión
            session()->flash('status', 'Las credenciales proporcionadas no coinciden con nuestros registros.');
            session()->flash('status-type', 'danger');

            return;
        }
    }
}; ?>

<div>
    <h4 class="text-white text-center mb-4">Iniciar Sesión</h4>

    @if (session('status'))
        <div class="alert alert-{{ session('status-type', 'success') }} d-flex align-items-center small mb-4">
            @if(session('status-type') === 'warning')
                <i class="fas fa-exclamation-triangle me-2"></i>
            @else
                <i class="fas fa-check-circle me-2"></i>
            @endif
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login">
        <!-- Email Address -->
        <div class="mb-4">
            <div class="form-floating">
                <input wire:model="form.email"
                       type="email"
                       id="email"
                       class="form-control form-control-lg bg-dark text-white border-secondary @error('form.email') is-invalid @enderror"
                       required
                       autocomplete="username"
                       placeholder="nombre@ejemplo.com">
                <label for="email" class="text-secondary">
                    <i class="fas fa-envelope me-2"></i>Correo electrónico
                </label>
                @error('form.email')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <div class="form-floating">
                <input wire:model="form.password"
                       type="password"
                       id="password"
                       class="form-control form-control-lg bg-dark text-white border-secondary @error('form.password') is-invalid @enderror"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••">
                <label for="password" class="text-secondary">
                    <i class="fas fa-lock me-2"></i>Contraseña
                </label>
                @error('form.password')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Remember Me -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input wire:model="form.remember"
                       type="checkbox"
                       id="remember"
                       class="form-check-input bg-dark border-secondary">
                <label class="form-check-label text-secondary" for="remember">
                    Recordarme
                </label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-primary text-decoration-none small"
                   wire:navigate>
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center"
                style="background-color: #22a7e1; border: none;">
            <i class="fas fa-sign-in-alt me-2"></i>
            Iniciar sesión
        </button>
    </form>
</div>
