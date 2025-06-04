<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Validator::extend('valid_dni', function ($attribute, $value, $parameters, $validator) {
            if (!preg_match('/^[0-9]{8}[A-Z]$/', $value)) {
                return false;
            }

            $numero = (int)substr($value, 0, 8);
            $letra = substr($value, 8, 1);

            $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

            $letraCalculada = $letras[$numero % 23];

            return $letra === $letraCalculada;
        });

        Validator::replacer('valid_dni', function ($message, $attribute, $rule, $parameters) {
            return 'El DNI no es válido. La letra no corresponde con el número.';
        });
    }
}
