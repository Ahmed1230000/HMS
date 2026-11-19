<?php

namespace App\Domains\Authorization\Policies\Roles;

use App\Models\User;
use App\Models\Roles;

class RolesPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }
    /**
     * Determine whether any models can be viewed.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('index-role');
    }

    /**
     * Determine whether the model can be viewed.
     */
    public function view(User $user ,Roles $role): bool
    {
        return $user->hasPermissionTo('show-role');
    }

    /**
     * Determine whether models can be created.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('store-role');
    }

    /**
     * Determine whether the model can be updated.
     */
    public function update(User $user, Roles $role): bool
    {
        return $user->hasPermissionTo('update-role');
    }

    /**
     * Determine whether the model can be deleted.
     */
    public function delete(User $user, Roles $role): bool
    {
        return $user->hasPermissionTo('delete-role');
    }

    /**
     * Determine whether the model can be restored.
     */
    public function restore(User $user, Roles $role): bool
    {
        return true;
    }

    /**
     * Determine whether the model can be permanently deleted.
     */
    public function forceDelete(User $user, Roles $role): bool
    {
        return true;
    }
}