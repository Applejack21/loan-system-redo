<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EquipmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Equipment $equipment): Response
    {
        return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Equipment $equipment): Response
    {
        return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Equipment $equipment): Response
    {
        return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Equipment $equipment): Response
    {
        return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Equipment $equipment): Response
    {
        return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
    }
}
