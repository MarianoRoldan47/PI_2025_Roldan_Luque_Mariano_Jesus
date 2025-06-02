<?php

namespace App\Policies;

use App\Models\Movimiento;
use App\Models\User;

class MovimientoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Movimiento $movimiento): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Movimiento $movimiento): bool
    {

        if ($user->rol === 'Administrador') {
            return true;
        }


        return $user->id === $movimiento->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Movimiento $movimiento): bool
    {

        if ($user->rol === 'Administrador') {
            return true;
        }

        
        return $user->id === $movimiento->user_id;
    }
}