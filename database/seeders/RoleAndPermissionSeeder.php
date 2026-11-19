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
        // 'admin',
        // 'employee',
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

        // 'admin' => [
        //     'user',
        //     'role',
        //     'permission',
        // ],

        // 'employee' => [
        //     'user',
        // ],
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

        $this->syncRolePermissions();

        $this->assignSuperAdminRole();

        // $this->assignPermissionToUser();
    }

    /**
     * --------------------------------------------------------------------------
     * Roles
     * --------------------------------------------------------------------------
     */

    private function createRoles(): void
    {
        Role::whereNotIn('name', $this->roles)->where('guard_name', 'api')->delete();

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
        Permission::whereNotIn('name', $this->permissions)->where('guard_name', 'api')->delete();
        
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

                $role->syncPermissions(Permission::all());

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

            $role->syncPermissions($permissions);
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

        $user->syncRoles('super_admin');
    }

    /**
     * --------------------------------------------------------------------------
     * Assign Permission To User
     * --------------------------------------------------------------------------
     */

    public function assignPermissionToUser(): void
    {
        $permission = Permission::where('name', Permission::all()->select('name'))->first();


        $user = User::where('email', 'super_admin@gmail.com')->first();

        if ($permission) {
            $user->syncPermissions([$permission]);
        }
    }
}
