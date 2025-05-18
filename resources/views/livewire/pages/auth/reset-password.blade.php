<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset($this->only('email', 'password', 'password_confirmation', 'token'), function ($user) {
            $user
                ->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])
                ->save();

            event(new PasswordReset($user));
        });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <h4 class="text-white text-center mb-4">{{ __('Restablecer Contraseña') }}</h4>

    <form wire:submit="resetPassword">
        <!-- Email Address -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-primary">
                    <i class="fas fa-envelope"></i>
                </span>
                <input wire:model="email" type="email" id="email"
                    class="form-control form-control-lg bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                    required autofocus autocomplete="username" placeholder="{{ __('Correo electrónico') }}">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-primary">
                    <i class="fas fa-lock"></i>
                </span>
                <input wire:model="password" type="password" id="password"
                    class="form-control form-control-lg bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                    required autocomplete="new-password" placeholder="{{ __('Nueva contraseña') }}">
            </div>
            @error('password')
                <div class="invalid-feedback d-block">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-primary">
                    <i class="fas fa-lock"></i>
                </span>
                <input wire:model="password_confirmation" type="password" id="password_confirmation"
                    class="form-control form-control-lg bg-dark text-white border-secondary @error('password_confirmation') is-invalid @enderror"
                    required autocomplete="new-password" placeholder="{{ __('Confirmar contraseña') }}">
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback d-block">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center"
            style="background-color: #22a7e1; border: none;">
            <i class="fas fa-key me-2"></i>
            {{ __('Restablecer Contraseña') }}
        </button>
    </form>
</div>
