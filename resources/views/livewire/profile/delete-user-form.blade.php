<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="card bg-dark shadow-lg border-0">
    <div class="card-body p-3 p-sm-4">
        <!-- Header -->
        <h4 class="text-white mb-2">{{ __('Eliminar Cuenta') }}</h4>
        <p class="text-white-50 small mb-4">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
        </p>

        <!-- Delete Button -->
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            <i class="fas fa-trash-alt me-2"></i>{{ __('Eliminar Cuenta') }}
        </button>

        <!-- Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark border-secondary">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title text-white" id="confirmDeleteModalLabel">
                            {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <form wire:submit="deleteUser">
                        <div class="modal-body">
                            <p class="text-white small mb-4">
                                {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor, introduce tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.') }}
                            </p>

                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-dark border-secondary">
                                        <i class="fas fa-lock text-danger"></i>
                                    </span>
                                    <input wire:model="password" type="password" id="password"
                                        class="form-control form-control-sm bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                                        required placeholder="{{ __('Contraseña') }}">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block small">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer border-secondary">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>{{ __('Cancelar') }}
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt me-2"></i>{{ __('Eliminar Cuenta') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
