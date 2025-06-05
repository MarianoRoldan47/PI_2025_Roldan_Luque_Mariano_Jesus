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
        //
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
            Storage::disk('public')->delete($user->imagen);
            $user->imagen = null;
            $user->save();
        }

        $this->redirect(request()->header('Referer'));
    }
};
?>

<div class="border-0 shadow-lg card bg-dark">
    <div class="p-3 card-body p-sm-4">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4 row">
                <div class="col-12">
                    <div class="gap-3 d-flex flex-column flex-sm-row align-items-center gap-sm-4">
                        <div class="mb-3 position-relative mb-sm-0">
                            <div class="overflow-hidden border image-upload-container rounded-circle border-secondary"
                                style="width: 120px; height: 120px;">
                                <img src="{{ Auth::user()->imagen ? asset('storage/' . Auth::user()->imagen) : asset('img/default-profile.png') }}"
                                    class="w-100 h-100 object-fit-cover">
                            </div>

                            <div class="mt-2 text-center">
                                <label for="imagen" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-camera me-1"></i> Cambiar foto
                                </label>
                                <input id="imagen" name="imagen" type="file" accept="image/*" class="d-none">
                            </div>
                        </div>

                        <div class="text-center text-sm-start">
                            <h3 class="mb-1 text-white fs-4">{{ auth()->user()->name }} {{ auth()->user()->apellido1 }}
                            </h3>
                            <span class="badge bg-primary">{{ Auth::user()->rol }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="dni" class="mb-1 text-white form-label small">DNI</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-id-card text-primary"></i>
                            </span>
                            <input name="dni" type="text" id="dni" value="{{ Auth::user()->dni }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('dni') is-invalid @enderror">
                        </div>
                        @error('dni')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="name" class="mb-1 text-white form-label small">Nombre</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <input name="name" type="text" id="name" value="{{ Auth::user()->name }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('name') is-invalid @enderror">
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="apellido1" class="mb-1 text-white form-label small">Primer Apellido</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <input name="apellido1" type="text" id="apellido1" value="{{ Auth::user()->apellido1 }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('apellido1') is-invalid @enderror">
                        </div>
                        @error('apellido1')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="apellido2" class="mb-1 text-white form-label small">Segundo Apellido</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <input name="apellido2" type="text" id="apellido2" value="{{ Auth::user()->apellido2 }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('apellido2') is-invalid @enderror">
                        </div>
                        @error('apellido2')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="telefono" class="mb-1 text-white form-label small">Teléfono</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-phone text-primary"></i>
                            </span>
                            <input name="telefono" type="text" id="telefono" value="{{ Auth::user()->telefono }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('telefono') is-invalid @enderror">
                        </div>
                        @error('telefono')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="email" class="mb-1 text-white form-label small">Correo Electrónico</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input name="email" type="email" id="email" value="{{ Auth::user()->email }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('email') is-invalid @enderror">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="direccion" class="mb-1 text-white form-label small">Dirección</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-location-dot text-primary"></i>
                            </span>
                            <input name="direccion" type="text" id="direccion"
                                value="{{ Auth::user()->direccion }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('direccion') is-invalid @enderror">
                        </div>
                        @error('direccion')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-2">
                    <div class="form-group">
                        <label for="codigo_postal" class="mb-1 text-white form-label small">Código Postal</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-map text-primary"></i>
                            </span>
                            <input name="codigo_postal" type="text" id="codigo_postal"
                                value="{{ Auth::user()->codigo_postal }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('codigo_postal') is-invalid @enderror">
                        </div>
                        @error('codigo_postal')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="localidad" class="mb-1 text-white form-label small">Localidad</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-city text-primary"></i>
                            </span>
                            <input name="localidad" type="text" id="localidad"
                                value="{{ Auth::user()->localidad }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('localidad') is-invalid @enderror">
                        </div>
                        @error('localidad')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="provincia" class="mb-1 text-white form-label small">Provincia</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-map-location-dot text-primary"></i>
                            </span>
                            <input name="provincia" type="text" id="provincia"
                                value="{{ Auth::user()->provincia }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('provincia') is-invalid @enderror">
                        </div>
                        @error('provincia')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="fecha_nacimiento" class="mb-1 text-white form-label small">Fecha de
                            Nacimiento</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-calendar text-primary"></i>
                            </span>
                            <input name="fecha_nacimiento" type="date" id="fecha_nacimiento"
                                value="{{ Auth::user()->fecha_nacimiento ? Auth::user()->fecha_nacimiento->format('Y-m-d') : '' }}"
                                class="form-control form-control-sm bg-dark text-white border-secondary @error('fecha_nacimiento') is-invalid @enderror">
                        </div>
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label class="mb-1 text-white form-label small">Rol</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary">
                                <i class="fas fa-user-shield text-primary"></i>
                            </span>
                            <div
                                class="text-white form-control form-control-sm bg-dark border-secondary user-select-none">
                                {{ Auth::user()->rol }}
                            </div>
                            <input type="hidden" name="rol" value="{{ Auth::user()->rol }}">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="is_approved" value="{{ Auth::user()->is_approved ? 1 : 0 }}">

                <div class="col-12">
                    <div
                        class="gap-2 pt-3 mt-2 d-flex flex-column flex-sm-row justify-content-end align-items-center gap-sm-3 border-top border-secondary pt-sm-4">
                        @if (session('status') && session('status-type') === 'success')
                            <div class="text-success small">
                                <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i> Guardar cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if (!Auth::user()->hasVerifiedEmail())
            <div class="p-2 mt-4 alert alert-warning d-flex align-items-center p-sm-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div class="small">
                    <div class="fw-bold">Tu correo electrónico no está verificado.</div>
                    <form action="{{ route('verification.send') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="p-0 btn btn-link text-warning small">
                            Haz clic aquí para reenviar el correo de verificación.
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const inputImagen = document.getElementById('imagen');
                const imgPreview = document.querySelector('.image-upload-container img');

                inputImagen.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();

                        reader.addEventListener('load', function() {
                            imgPreview.src = reader.result;
                        });

                        reader.readAsDataURL(file);
                    }
                });
            });
        </script>
    @endpush
</div>
