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
        return true; // Todos los usuarios autenticados pueden ver la lista
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Movimiento $movimiento): bool
    {
        return true; // Todos los usuarios autenticados pueden ver los detalles
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Todos los usuarios autenticados pueden crear
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Movimiento $movimiento): bool
    {
        // Administradores pueden editar cualquier movimiento
        if ($user->rol === 'Administrador') {
            return true;
        }

        // Usuarios normales solo pueden editar sus propios movimientos
        return $user->id === $movimiento->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Movimiento $movimiento): bool
    {
        // Administradores pueden eliminar cualquier movimiento
        if ($user->rol === 'Administrador') {
            return true;
        }

        // Usuarios normales solo pueden eliminar sus propios movimientos
        return $user->id === $movimiento->user_id;
    }
}
