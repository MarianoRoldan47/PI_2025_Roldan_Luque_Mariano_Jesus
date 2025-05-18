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
        foreach ([
            'dni','name','apellido1','apellido2','telefono',
            'direccion','codigo_postal','localidad','provincia',
            'rol','email','fecha_nacimiento'
        ] as $field) {
            $this->{$field} = $user->{$field};
        }
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'dni'              => ['required','string','size:9', Rule::unique(User::class)->ignore($user->id)],
            'name'             => ['required','string','max:255'],
            'apellido1'        => ['required','string','max:255'],
            'apellido2'        => ['nullable','string','max:255'],
            'telefono'         => ['required','string','size:9'],
            'direccion'        => ['required','string','max:255'],
            'codigo_postal'    => ['required','string','size:5'],
            'localidad'        => ['required','string','max:255'],
            'provincia'        => ['required','string','max:255'],
            'rol'              => ['required', Rule::in(['Administrador','Operador','Supervisor'])],
            'email'            => ['required','email','max:255', Rule::unique(User::class)->ignore($user->id)],
            'fecha_nacimiento' => ['required','date'],
            'imagen'           => ['nullable','image','max:1024'],
        ]);

        // Actualizar manualmente sin fillable
        $user->dni              = $validated['dni'];
        $user->name             = $validated['name'];
        $user->apellido1        = $validated['apellido1'];
        $user->apellido2        = $validated['apellido2'] ?? null;
        $user->telefono         = $validated['telefono'];
        $user->direccion        = $validated['direccion'];
        $user->codigo_postal    = $validated['codigo_postal'];
        $user->localidad        = $validated['localidad'];
        $user->provincia        = $validated['provincia'];
        $user->rol              = $validated['rol'];

        // Email y verificación
        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
        }

        $user->fecha_nacimiento = $validated['fecha_nacimiento'];

        // Procesar imagen si se sube
        if ($this->imagen) {
            if ($user->imagen) {
                \Storage::disk('public')->delete($user->imagen);
            }
            $path = $this->imagen->store('imagenes/perfiles', 'public');
            $user->imagen = $path;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();
        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            Session::flash('status', 'verification-link-sent');
        }
    }
};
?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del perfil') }}
        </h2>
        <p class="mt-1 text-sm text-white">
            {{ __('Actualiza los datos de tu cuenta.') }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6" enctype="multipart/form-data">
        @foreach ([
        'dni' => 'DNI',
        'name' => 'Nombre',
        'apellido1' => 'Primer Apellido',
        'apellido2' => 'Segundo Apellido',
        'telefono' => 'Teléfono',
        'direccion' => 'Dirección',
        'codigo_postal' => 'Código Postal',
        'localidad' => 'Localidad',
        'provincia' => 'Provincia',
    ] as $field => $label)
            <div>
                <x-input-label :for="$field" :value="__($label)" />
                <x-text-input wire:model="{{ $field }}" id="{{ $field }}" name="{{ $field }}"
                    type="text" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get($field)" />
            </div>
        @endforeach

        <div>
            <x-input-label for="rol" value="Rol" />
            <select wire:model="rol" id="rol" name="rol"
                class="w-full px-3 py-2 bg-[#22a7e1] text-white border border-[#22a7e1] rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition">
                <option value="">Seleccione un rol</option>
                <option value="Administrador">Administrador</option>
                <option value="Operador">Operador</option>
                <option value="Supervisor">Supervisor</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('rol')" />
        </div>


        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="fecha_nacimiento" value="Fecha de Nacimiento" />
            <x-text-input wire:model="fecha_nacimiento" id="fecha_nacimiento" name="fecha_nacimiento" type="date"
                class="mt-1 block w-full" required />
            <x-input-error class="mt-2" :messages="$errors->get('fecha_nacimiento')" />
        </div>

        <div>
            <x-input-label for="imagen" value="Imagen de Perfil" />
            <input type="file" wire:model="imagen" id="imagen" class="mt-1 block w-full text-white"
                accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('imagen')" />
        </div>

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Tu correo electrónico no está verificado.') }}
                    <button wire:click.prevent="sendVerification"
                        class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('Se ha enviado un nuevo enlace de verificación.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Guardado.') }}
            </x-action-message>
        </div>
    </form>
</section>
