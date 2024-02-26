<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role = Role::create(['name' => 'owner']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);

    }
}