<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.guest')] class extends Component
{
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
            'dni' => ['required', 'string', 'size:9', 'unique:'.User::class],
            'name' => ['required', 'string', 'max:255'],
            'apellido1' => ['required', 'string', 'max:255'],
            'apellido2' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
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

        event(new Registered($user = User::create($validated)));

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
                <div class="form-floating">
                    <input wire:model="dni"
                           type="text"
                           id="dni"
                           class="form-control bg-dark text-white border-secondary @error('dni') is-invalid @enderror"
                           required
                           placeholder="12345678A">
                    <label class="text-secondary">
                        <i class="fas fa-id-card me-2"></i>DNI
                    </label>
                    @error('dni')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Nombre -->
            <div class="col-md-6">
                <div class="form-floating">
                    <input wire:model="name"
                           type="text"
                           id="name"
                           class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror"
                           required
                           placeholder="Tu nombre">
                    <label class="text-secondary">
                        <i class="fas fa-user me-2"></i>Nombre
                    </label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Apellidos -->
            <div class="col-md-6">
                <div class="form-floating">
                    <input wire:model="apellido1"
                           type="text"
                           id="apellido1"
                           class="form-control bg-dark text-white border-secondary @error('apellido1') is-invalid @enderror"
                           required
                           placeholder="Primer apellido">
                    <label class="text-secondary">
                        <i class="fas fa-user me-2"></i>Primer Apellido
                    </label>
                    @error('apellido1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating">
                    <input wire:model="apellido2"
                           type="text"
                           id="apellido2"
                           class="form-control bg-dark text-white border-secondary @error('apellido2') is-invalid @enderror"
                           placeholder="Segundo apellido">
                    <label class="text-secondary">
                        <i class="fas fa-user me-2"></i>Segundo Apellido
                    </label>
                    @error('apellido2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <div class="form-floating">
                    <input wire:model="email"
                           type="email"
                           id="email"
                           class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                           required
                           placeholder="correo@ejemplo.com">
                    <label class="text-secondary">
                        <i class="fas fa-envelope me-2"></i>Correo Electrónico
                    </label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Teléfono -->
            <div class="col-md-6">
                <div class="form-floating">
                    <input wire:model="telefono"
                           type="tel"
                           id="telefono"
                           class="form-control bg-dark text-white border-secondary @error('telefono') is-invalid @enderror"
                           required
                           placeholder="123456789">
                    <label class="text-secondary">
                        <i class="fas fa-phone me-2"></i>Teléfono
                    </label>
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dirección -->
            <div class="col-12">
                <div class="form-floating">
                    <input wire:model="direccion"
                           type="text"
                           id="direccion"
                           class="form-control bg-dark text-white border-secondary @error('direccion') is-invalid @enderror"
                           required
                           placeholder="Tu dirección">
                    <label class="text-secondary">
                        <i class="fas fa-home me-2"></i>Dirección
                    </label>
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Código Postal -->
            <div class="col-md-4">
                <div class="form-floating">
                    <input wire:model="codigo_postal"
                           type="text"
                           id="codigo_postal"
                           class="form-control bg-dark text-white border-secondary @error('codigo_postal') is-invalid @enderror"
                           required
                           placeholder="12345">
                    <label class="text-secondary">
                        <i class="fas fa-map-marker-alt me-2"></i>Código Postal
                    </label>
                    @error('codigo_postal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Localidad -->
            <div class="col-md-4">
                <div class="form-floating">
                    <input wire:model="localidad"
                           type="text"
                           id="localidad"
                           class="form-control bg-dark text-white border-secondary @error('localidad') is-invalid @enderror"
                           required
                           placeholder="Tu localidad">
                    <label class="text-secondary">
                        <i class="fas fa-city me-2"></i>Localidad
                    </label>
                    @error('localidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Provincia -->
            <div class="col-md-4">
                <div class="form-floating">
                    <input wire:model="provincia"
                           type="text"
                           id="provincia"
                           class="form-control bg-dark text-white border-secondary @error('provincia') is-invalid @enderror"
                           required
                           placeholder="Tu provincia">
                    <label class="text-secondary">
                        <i class="fas fa-map me-2"></i>Provincia
                    </label>
                    @error('provincia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Fecha de Nacimiento -->
            <div class="col-md-6">
                <div class="form-floating">
                    <input wire:model="fecha_nacimiento"
                           type="date"
                           id="fecha_nacimiento"
                           class="form-control bg-dark text-white border-secondary @error('fecha_nacimiento') is-invalid @enderror"
                           required>
                    <label class="text-secondary">
                        <i class="fas fa-calendar me-2"></i>Fecha de Nacimiento
                    </label>
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Contraseña -->
            <div class="col-md-6">
                <div class="form-floating">
                    <input wire:model="password"
                           type="password"
                           id="password"
                           class="form-control bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                           required
                           placeholder="••••••••">
                    <label class="text-secondary">
                        <i class="fas fa-lock me-2"></i>Contraseña
                    </label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="col-md-6">
                <div class="form-floating">
                    <input wire:model="password_confirmation"
                           type="password"
                           id="password_confirmation"
                           class="form-control bg-dark text-white border-secondary"
                           required
                           placeholder="••••••••">
                    <label class="text-secondary">
                        <i class="fas fa-lock me-2"></i>Confirmar Contraseña
                    </label>
                </div>
            </div>

            <!-- Imagen de perfil -->
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label text-white-50">
                        <i class="fas fa-image me-2"></i>Imagen de perfil
                    </label>
                    <input wire:model="imagen"
                           type="file"
                           class="form-control bg-dark text-white border-secondary @error('imagen') is-invalid @enderror"
                           accept="image/*">
                    @error('imagen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="col-12">
                <button type="submit"
                        class="btn btn-primary w-100"
                        style="background-color: #22a7e1; border: none;">
                    <i class="fas fa-user-plus me-2"></i>
                    Registrarse
                </button>
            </div>
        </div>
    </form>
</div>
