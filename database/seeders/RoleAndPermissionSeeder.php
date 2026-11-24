<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    use RefreshDatabase;
    /**
     * Available Roles
     */
    private array $roles = [
        'super_admin',
    ];

    private array $SuperAdminPermissions = [
        'manage-roles',
        'manage-permissions',
    ];

    /**
     * Modules
     */
    private array $permissions = [
        'user',
        'role',
        'permission',
    ];

    /**
     * Role => Modules
     */
    private array $rolePermissions = [
        'super_admin' => ['*'],
    ];

    /**
     * CRUD Actions
     */
    private array $actions = [
        'index',
        'store',
        'show',
        'update',
        'delete',
    ];

    public function run(): void
    {
        $this->createRoles();

        $this->createPermissions();

        $this->superAdminPermissions();

        $this->syncRolePermissions();

        $this->assignSuperAdminRole();
    }

    /**
     * --------------------------------------------------------------------------
     * Roles
     * --------------------------------------------------------------------------
     */

    private function superAdminPermissions(): void
    {
        foreach ($this->SuperAdminPermissions as $permission) {

            Permission::firstOrCreate([
                'name'       => strtolower($permission),
                'guard_name' => 'api',
            ]);
        }
    }

    private function createRoles(): void
    {
        foreach ($this->roles as $role) {

            Role::firstOrCreate([
                'name'       => strtolower($role),
                'guard_name' => 'api',
            ]);
        }
    }

    /**
     * --------------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------------
     */

    private function createPermissions(): void
    {
        foreach ($this->permissions as $module) {

            foreach ($this->actions as $action) {

                Permission::firstOrCreate([
                    'name'       => "{$action}-" . strtolower($module),
                    'guard_name' => 'api',
                ]);
            }
        }
    }

    /**
     * --------------------------------------------------------------------------
     * Sync Role Permissions
     * --------------------------------------------------------------------------
     */

    private function syncRolePermissions(): void
    {
        foreach ($this->rolePermissions as $roleName => $modules) {

            $role = Role::where('name', strtolower($roleName))->first();

            if (! $role) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Super Admin
            |--------------------------------------------------------------------------
            */

            if (in_array('*', $modules)) {

                $role->givePermissionTo(
                    Permission::where('guard_name', 'api')->get()
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Build Permission Names
            |--------------------------------------------------------------------------
            */

            $permissions = [];

            foreach ($modules as $module) {

                foreach ($this->actions as $action) {

                    $permissions[] = "{$action}-" . strtolower($module);
                }
            }

            $role->givePermissionsTo($permissions);
        }
    }

    /**
     * --------------------------------------------------------------------------
     * Assign Role To User
     * --------------------------------------------------------------------------
     */

    private function assignSuperAdminRole(): void
    {
        $user = User::where('email', 'super_admin@gmail.com')->first();

        if (! $user) {
            return;
        }

        $user->assignRole('super_admin');
    }
}
