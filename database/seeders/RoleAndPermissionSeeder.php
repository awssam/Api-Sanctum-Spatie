<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ['name'=>'create','guard_name' => 'sanctum'],
            ['name'=>'read','guard_name' => 'sanctum'],
            ['name'=>'update','guard_name' => 'sanctum'],
            ['name'=>'delete','guard_name' => 'sanctum'],
        ];

        foreach ($permissions as $permission){
            Permission::updateOrCreate($permission,$permission);
        }

        // this can be done as separate statements
        $role = Role::create(['name' => 'super-admin','guard_name' => 'sanctum']);
        $role->givePermissionTo(Permission::all());

        // or may be done by chaining
        Role::create(['name' => 'manager','guard_name' => 'sanctum'])->givePermissionTo(['create','read','update']);

        $role = Role::create(['name' => 'moderator','guard_name' => 'sanctum']);
        $role->givePermissionTo('read','update');

        $role = Role::create(['name' => 'guest','guard_name' => 'sanctum']);
        $role->givePermissionTo('read');
    }
}
