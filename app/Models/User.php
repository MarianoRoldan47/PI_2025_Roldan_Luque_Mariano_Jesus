<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [

        'dni',
        'name',
        'apellido1',
        'apellido2',
        'telefono',
        'direccion',
        'codigo_postal',
        'localidad',
        'provincia',
        'rol',
        'email',
        'password',
        'is_approved',
        'approved_at',
        'imagen',
        'fecha_nacimiento'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'fecha_nacimiento' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}
