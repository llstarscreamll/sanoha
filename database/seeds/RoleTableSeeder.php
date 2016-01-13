<?php

use Illuminate\Database\Seeder;
use sanoha\Models\Role;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->delete();

        $user = new Role();
        $user->name         = 'user';
        $user->display_name = 'Usuario';
        $user->description  = 'Usuario con permisos restringidos.';
        $user->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Administrator';
        $admin->description  = 'Usuario con permisos sobre la mayoría de las funciones del sistema.';
        $admin->save();
        
        $permissions = \sanoha\Models\Permission::lists('id')->all();
        
        $admin->perms()->sync($permissions);
    }
}
