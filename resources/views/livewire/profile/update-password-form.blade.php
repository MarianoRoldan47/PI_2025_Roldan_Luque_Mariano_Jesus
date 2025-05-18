<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        // Añadir mensaje a la sesión flash
        Session::flash('success', 'La contraseña se ha actualizado correctamente.');

        // Redirigir a la misma página para mostrar el modal
        $this->redirect(request()->header('Referer'), navigate: true);
    }
}; ?>

<section class="card bg-dark shadow-lg border-0">
    <div class="card-body p-3 p-sm-4">
        <!-- Header -->
        <h4 class="text-white mb-1">{{ __('Actualizar Contraseña') }}</h4>
        <p class="text-white-50 small mb-4">
            {{ __('Asegúrate de usar una contraseña segura para mantener tu cuenta protegida.') }}
        </p>

        <form wire:submit="updatePassword" class="mt-4">
            <!-- Contraseña actual -->
            <div class="form-group mb-3">
                <label for="current_password" class="form-label text-white small mb-1">
                    {{ __('Contraseña Actual') }}
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-dark border-secondary">
                        <i class="fas fa-lock text-primary"></i>
                    </span>
                    <input wire:model="current_password" type="password" id="current_password"
                        class="form-control form-control-sm bg-dark text-white border-secondary @error('current_password') is-invalid @enderror"
                        required>
                </div>
                @error('current_password')
                    <div class="invalid-feedback d-block small">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Nueva contraseña -->
            <div class="form-group mb-3">
                <label for="password" class="form-label text-white small mb-1">
                    {{ __('Nueva Contraseña') }}
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-dark border-secondary">
                        <i class="fas fa-key text-primary"></i>
                    </span>
                    <input wire:model="password" type="password" id="password"
                        class="form-control form-control-sm bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                        required>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block small">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirmar contraseña -->
            <div class="form-group mb-4">
                <label for="password_confirmation" class="form-label text-white small mb-1">
                    {{ __('Confirmar Contraseña') }}
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-dark border-secondary">
                        <i class="fas fa-check-double text-primary"></i>
                    </span>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation"
                        class="form-control form-control-sm bg-dark text-white border-secondary" required>
                </div>
            </div>

            <!-- Footer del formulario -->
            <div
                class="d-flex flex-column flex-sm-row justify-content-end align-items-center gap-2 gap-sm-3 border-top border-secondary mt-4 pt-3 pt-sm-4">
                <div wire:loading class="text-primary small">
                    <i class="fas fa-spinner fa-spin me-1"></i> {{ __('Guardando...') }}
                </div>

                <div wire:loading.remove>
                    @if (session('status') === 'password-updated')
                        <div class="text-success small">
                            <i class="fas fa-check-circle me-1"></i> {{ __('Guardado.') }}
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i> {{ __('Guardar cambios') }}
                </button>
            </div>
        </form>
    </div>
</section>
