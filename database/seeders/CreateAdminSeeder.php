<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'name' => 'samaa',
            'email' => 'samaaabdelmged55@gmail.com',
            'password' => bcrypt('12345678'),
            'code' => null,
            'expire_at' => null,
            'sendonce' => true,
            'role_id' => 1,
        ]);
        $id = 1;
        $role = Role::find($id);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

    }
}
