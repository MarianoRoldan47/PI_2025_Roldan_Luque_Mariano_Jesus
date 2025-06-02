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


        Session::flash('success', 'La contraseña se ha actualizado correctamente.');

        
        $this->redirect(request()->header('Referer'), navigate: true);
    }
}; ?>

<section class="border-0 shadow-lg card bg-dark">
    <div class="p-3 card-body p-sm-4">

        <h4 class="mb-1 text-white">{{ __('Actualizar Contraseña') }}</h4>
        <p class="mb-4 text-white-50 small">
            {{ __('Asegúrate de usar una contraseña segura para mantener tu cuenta protegida.') }}
        </p>

        <form wire:submit="updatePassword" class="mt-4">

            <div class="mb-3 form-group">
                <label for="current_password" class="mb-1 text-white form-label small">
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


            <div class="mb-3 form-group">
                <label for="password" class="mb-1 text-white form-label small">
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


            <div class="mb-4 form-group">
                <label for="password_confirmation" class="mb-1 text-white form-label small">
                    {{ __('Confirmar Contraseña') }}
                </label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-dark border-secondary">
                        <i class="fas fa-check-double text-primary"></i>
                    </span>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation"
                        class="text-white form-control form-control-sm bg-dark border-secondary" required>
                </div>
            </div>


            <div
                class="gap-2 pt-3 mt-4 d-flex flex-column flex-sm-row justify-content-end align-items-center gap-sm-3 border-top border-secondary pt-sm-4">
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
