<?php

namespace App\Domains\Authorization\Policies\Permission;

use App\Models\User;
use App\Models\Permission;

class PermissionPolicy
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
        return $user->hasPermissionTo('index-permission');
    }

    /**
     * Determine whether the model can be viewed.
     */
    public function view(User $user ,Permission $permission): bool
    {
        return $user->hasPermissionTo('show-permission');
    }

    /**
     * Determine whether models can be created.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('store-permission');
    }

    /**
     * Determine whether the model can be updated.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('update-permission');
    }

    /**
     * Determine whether the model can be deleted.
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('delete-permission');
    }

    /**
     * Determine whether the model can be restored.
     */
    public function restore(User $user, Permission $permission): bool
    {
        return true;
    }

    /**
     * Determine whether the model can be permanently deleted.
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return true;
    }
}