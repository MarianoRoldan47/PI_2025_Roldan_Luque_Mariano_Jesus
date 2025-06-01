<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Notifications\NuevaSolicitudUsuario;

new #[Layout('layouts.guest')] class extends Component {
    use WithFileUploads;

    public string $dni = '';
    public string $name = '';
    public string $apellido1 = '';
    public string $apellido2 = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $telefono = '';
    public string $direccion = '';
    public string $codigo_postal = '';
    public string $localidad = '';
    public string $provincia = '';
    public $fecha_nacimiento = '';
    public $imagen;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'dni' => ['required', 'string', 'size:9', 'unique:' . User::class],
            'name' => ['required', 'string', 'max:255'],
            'apellido1' => ['required', 'string', 'max:255'],
            'apellido2' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'telefono' => ['required', 'string', 'size:9'],
            'direccion' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['required', 'string', 'size:5'],
            'localidad' => ['required', 'string', 'max:255'],
            'provincia' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'imagen' => ['nullable', 'image', 'max:1024'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['rol'] = 'Usuario';

        if ($this->imagen) {
            $validated['imagen'] = $this->imagen->store('imagenes/perfiles', 'public');
        }

        $user = User::create($validated);

        // Enviar notificación a todos los administradores
        try {
            $admins = User::where('rol', 'Administrador')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NuevaSolicitudUsuario($user));
            }
        } catch (Exception $e) {
            // Registrar el error pero permitir que continúe
            Log::error('Error al enviar correo a administradores: ' . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>
<div>
    <h4 class="text-white text-center mb-4">Registro</h4>

    <form wire:submit="register" class="mt-4">
        <div class="row g-3">
            <!-- DNI -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="dni" class="form-label text-white">DNI</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-id-card text-white"></i>
                        </span>
                        <input wire:model="dni" type="text" id="dni"
                            class="form-control bg-dark text-white border-secondary @error('dni') is-invalid @enderror"
                            required>
                    </div>
                    @error('dni')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Nombre -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="name" class="form-label text-white">Nombre</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-user text-white"></i>
                        </span>
                        <input wire:model="name" type="text" id="name"
                            class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror"
                            required>
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Apellidos -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="apellido1" class="form-label text-white">Primer Apellido</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-user text-white"></i>
                        </span>
                        <input wire:model="apellido1" type="text" id="apellido1"
                            class="form-control bg-dark text-white border-secondary @error('apellido1') is-invalid @enderror"
                            required>
                    </div>
                    @error('apellido1')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="apellido2" class="form-label text-white">Segundo Apellido</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-user text-white"></i>
                        </span>
                        <input wire:model="apellido2" type="text" id="apellido2"
                            class="form-control bg-dark text-white border-secondary @error('apellido2') is-invalid @enderror">
                    </div>
                    @error('apellido2')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="email" class="form-label text-white">Correo Electrónico</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-envelope text-white"></i>
                        </span>
                        <input wire:model="email" type="email" id="email"
                            class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                            required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Teléfono -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="telefono" class="form-label text-white">Teléfono</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-phone text-white"></i>
                        </span>
                        <input wire:model="telefono" type="tel" id="telefono"
                            class="form-control bg-dark text-white border-secondary @error('telefono') is-invalid @enderror"
                            required>
                    </div>
                    @error('telefono')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dirección -->
            <div class="col-12">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="direccion" class="form-label text-white">Dirección</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-home text-white"></i>
                        </span>
                        <input wire:model="direccion" type="text" id="direccion"
                            class="form-control bg-dark text-white border-secondary @error('direccion') is-invalid @enderror"
                            required>
                    </div>
                    @error('direccion')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Código Postal, Localidad, Provincia -->
            <div class="col-md-4">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="codigo_postal" class="form-label text-white">Código Postal</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </span>
                        <input wire:model="codigo_postal" type="text" id="codigo_postal"
                            class="form-control bg-dark text-white border-secondary @error('codigo_postal') is-invalid @enderror"
                            required>
                    </div>
                    @error('codigo_postal')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="localidad" class="form-label text-white">Localidad</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-city text-white"></i>
                        </span>
                        <input wire:model="localidad" type="text" id="localidad"
                            class="form-control bg-dark text-white border-secondary @error('localidad') is-invalid @enderror"
                            required>
                    </div>
                    @error('localidad')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="provincia" class="form-label text-white">Provincia</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-map text-white"></i>
                        </span>
                        <input wire:model="provincia" type="text" id="provincia"
                            class="form-control bg-dark text-white border-secondary @error('provincia') is-invalid @enderror"
                            required>
                    </div>
                    @error('provincia')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Fecha de Nacimiento y Contraseña -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="fecha_nacimiento" class="form-label text-white">Fecha de Nacimiento</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-calendar text-white"></i>
                        </span>
                        <input wire:model="fecha_nacimiento" type="date" id="fecha_nacimiento"
                            class="form-control bg-dark text-white border-secondary @error('fecha_nacimiento') is-invalid @enderror"
                            required>
                    </div>
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="password" class="form-label text-white">Contraseña</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-lock text-white"></i>
                        </span>
                        <input wire:model="password" type="password" id="password"
                            class="form-control bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                            required>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Confirmar Contraseña e Imagen -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="password_confirmation" class="form-label text-white">Confirmar Contraseña</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-lock text-white"></i>
                        </span>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation"
                            class="form-control bg-dark text-white border-secondary" required>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="label-container" style="min-height: 24px;">
                        <label for="imagen" class="form-label text-white">Imagen de perfil</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary">
                            <i class="fas fa-image text-white"></i>
                        </span>
                        <input wire:model="imagen" type="file" id="imagen"
                            class="form-control bg-dark text-white border-secondary @error('imagen') is-invalid @enderror"
                            accept="image/*">
                    </div>
                    <div class="form-text text-white-50 small">Formatos permitidos: JPG, PNG. Máximo 1MB.</div>
                    @error('imagen')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-primary w-100 py-2"
                    style="background-color: #22a7e1; border: none;">
                    <i class="fas fa-user-plus me-2"></i>
                    Registrarse
                </button>
            </div>

            <div class="col-12 mt-3 text-center">
                <p class="text-white mb-0">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="text-info fw-bold" wire:navigate>
                        Iniciar sesión
                    </a>
                </p>
            </div>
        </div>
    </form>
</div>
