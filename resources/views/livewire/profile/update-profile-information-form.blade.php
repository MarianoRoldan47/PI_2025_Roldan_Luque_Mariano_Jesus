<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $dni = '';
    public string $name = '';
    public string $apellido1 = '';
    public string $apellido2 = '';
    public string $telefono = '';
    public string $direccion = '';
    public string $codigo_postal = '';
    public string $localidad = '';
    public string $provincia = '';
    public string $rol = '';
    public string $email = '';
    public string $fecha_nacimiento = '';
    public ?\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $imagen = null;

    public function mount(): void
    {
        $user = Auth::user();

        foreach (['dni', 'name', 'apellido1', 'apellido2', 'telefono', 'direccion', 'codigo_postal', 'localidad', 'provincia', 'rol', 'email'] as $field) {
            $this->{$field} = $user->{$field} ?? '';
        }

        if ($user->fecha_nacimiento) {
            $this->fecha_nacimiento = $user->fecha_nacimiento->format('Y-m-d');
        } else {
            $this->fecha_nacimiento = '';
        }
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'dni' => ['required', 'string', 'size:9', Rule::unique(User::class)->ignore($user->id)],
            'name' => ['required', 'string', 'max:255'],
            'apellido1' => ['required', 'string', 'max:255'],
            'apellido2' => ['nullable', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'size:9'],
            'direccion' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['required', 'string', 'size:5'],
            'localidad' => ['required', 'string', 'max:255'],
            'provincia' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'fecha_nacimiento' => ['required', 'date'],
            'imagen' => ['nullable', 'image', 'max:1024'],
        ]);

        $user->dni = $validated['dni'];
        $user->name = $validated['name'];
        $user->apellido1 = $validated['apellido1'];
        $user->apellido2 = $validated['apellido2'] ?? null;
        $user->telefono = $validated['telefono'];
        $user->direccion = $validated['direccion'];
        $user->codigo_postal = $validated['codigo_postal'];
        $user->localidad = $validated['localidad'];
        $user->provincia = $validated['provincia'];

        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
        }

        $user->fecha_nacimiento = $validated['fecha_nacimiento'];

        if ($this->imagen) {
            if ($user->imagen) {
                Storage::disk('public')->delete($user->imagen);
            }
            $directory = 'imagenes/perfiles';
            $fileName = time() . '_' . $this->dni . '_' . $this->name;
            $path = $this->imagen->storeAs($directory, $fileName, 'public');
        }

        $user->save();

        $this->redirect(request()->header('Referer'));
    }

    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            redirect()->intended(route('profile', absolute: false));

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        session()->flash('success', 'Se ha enviado un nuevo enlace de verificación a tu correo electrónico.');

        $this->dispatch('verification-link-sent');
    }
    public function deleteImage(): void
    {
        $user = Auth::user();

        if ($this->imagen) {
            $this->imagen = null;
        }

        if ($user->imagen) {
            \Storage::disk('public')->delete($user->imagen);
            $user->imagen = null;
            $user->save();
        }

        $this->redirect(request()->header('Referer'));
    }
};
?>

<section class="border-0 shadow-lg card bg-dark">
    <div class="p-3 card-body p-sm-4">

        <div class="mb-4 row">
            <div class="col-12">
                <div class="gap-3 d-flex flex-column flex-sm-row align-items-center gap-sm-4">

                    <div class="mb-3 position-relative mb-sm-0">
                        <div class="overflow-hidden border image-upload-container rounded-circle border-secondary"
                            style="width: 120px; height: 120px;">
                            @if ($imagen)
                                <img src="{{ $imagen->temporaryUrl() }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <img src="{{ Auth::user()->imagen ? asset('storage/' . Auth::user()->imagen) : asset('img/default-profile.png') }}"
                                    class="w-100 h-100 object-fit-cover">
                            @endif


                            <label for="imagen"
                                class="mb-0 image-upload-overlay d-flex align-items-center justify-content-center">
                                <i class="fas fa-camera fs-4"></i>
                                <input type="file" wire:model="imagen" id="imagen" class="d-none"
                                    accept="image/*">
                            </label>
                        </div>


                        @if (Auth::user()->imagen || $imagen)
                            <button type="button" wire:click="deleteImage"
                                class="top-0 p-0 shadow-sm position-absolute end-0 btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 24px; height: 24px; transform: translate(25%, -25%);"
                                title="Eliminar imagen">
                                <i class="fas fa-times small"></i>
                            </button>
                        @endif
                    </div>

                    <div class="text-center text-sm-start">
                        <h3 class="mb-1 text-white fs-4">{{ auth()->user()->name }} {{ auth()->user()->apellido1 }}</h3>
                        <span class="badge bg-primary">{{ Auth::user()->rol }}</span>
                        @if ($imagen)
                            <div class="mt-2 text-success small">
                                <i class="fas fa-check-circle me-1"></i> Nueva imagen seleccionada
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <form wire:submit="updateProfileInformation">
            <div class="row g-3">
                @foreach ([
        'dni' => ['label' => 'DNI', 'icon' => 'fa-id-card', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 4]],
        'name' => ['label' => 'Nombre', 'icon' => 'fa-user', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 4]],
        'apellido1' => ['label' => 'Primer Apellido', 'icon' => 'fa-user', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 4]],
        'apellido2' => ['label' => 'Segundo Apellido', 'icon' => 'fa-user', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 4]],
        'telefono' => ['label' => 'Teléfono', 'icon' => 'fa-phone', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 4]],
        'email' => ['label' => 'Correo electrónico', 'icon' => 'fa-envelope', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 4]],
        'direccion' => ['label' => 'Dirección', 'icon' => 'fa-location-dot', 'col' => ['xs' => 12, 'md' => 6]],
        'codigo_postal' => ['label' => 'Código Postal', 'icon' => 'fa-map', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 2]],
        'localidad' => ['label' => 'Localidad', 'icon' => 'fa-city', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 6]],
        'provincia' => ['label' => 'Provincia', 'icon' => 'fa-map-location-dot', 'col' => ['xs' => 12, 'sm' => 6, 'md' => 6]],
    ] as $field => $config)
                    <div
                        class="col-{{ $config['col']['xs'] }} col-sm-{{ $config['col']['sm'] ?? $config['col']['xs'] }} col-md-{{ $config['col']['md'] ?? ($config['col']['sm'] ?? $config['col']['xs']) }}">
                        <div class="form-group">
                            <label for="{{ $field }}" class="mb-1 text-white form-label small">
                                {{ __($config['label']) }}
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-dark border-secondary">
                                    <i class="fas {{ $config['icon'] }} text-primary"></i>
                                </span>
                                <input wire:model="{{ $field }}" type="text" id="{{ $field }}"
                                    class="form-control form-control-sm bg-dark text-white border-secondary @error($field) is-invalid @enderror">
                            </div>
                            @error($field)
                                <div class="invalid-feedback d-block small">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                @endforeach


                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="fecha_nacimiento" class="mb-1 text-white form-label small">
                            {{ __('Fecha de Nacimiento') }}
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-calendar text-primary"></i>
                            </span>
                            <input wire:model="fecha_nacimiento" type="date" id="fecha_nacimiento"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('fecha_nacimiento') is-invalid @enderror">
                        </div>
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback d-block small">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>


                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label class="mb-1 text-white form-label small">
                            {{ __('Rol') }}
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-user-shield text-primary"></i>
                            </span>
                            <div
                                class="text-white form-control form-control-sm bg-dark border-secondary user-select-none">
                                {{ Auth::user()->rol }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @if (!Auth::user()->hasVerifiedEmail())
                <div class="p-2 mt-4 alert alert-warning d-flex align-items-center p-sm-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div class="small">
                        <div class="fw-bold">{{ __('Tu correo electrónico no está verificado.') }}</div>
                        <button wire:click.prevent="sendVerification" class="p-0 btn btn-link text-warning small">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </div>
                </div>
            @endif


            <div
                class="gap-2 pt-3 mt-4 d-flex flex-column flex-sm-row justify-content-end align-items-center gap-sm-3 border-top border-secondary pt-sm-4">
                <div wire:loading class="text-primary small">
                    <i class="fas fa-spinner fa-spin me-1"></i> Guardando...
                </div>

                <div wire:loading.remove>
                    @if (session()->has('message'))
                        <div class="text-success small">
                            <i class="fas fa-check-circle me-1"></i> {{ session('message') }}
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i> {{ __('Guardar cambios') }}
                </button>
            </div>
        </form>
    </div>
    @push('styles')
        <style>
            .image-upload-container {
                position: relative;
                cursor: pointer;
            }

            .image-upload-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                opacity: 0;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .image-upload-container:hover .image-upload-overlay {
                opacity: 1;
            }

            .btn.rounded-circle {
                transition: all 0.2s ease;
            }

            .btn.rounded-circle:hover {
                transform: translate(25%, -25%) scale(1.1) !important;
            }
        </style>
    @endpush
</section>
