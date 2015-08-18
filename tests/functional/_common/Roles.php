<?php namespace common;

use \sanoha\Models\Role as RoleModel;
use \sanoha\Models\Permission as PermissionModel;

class Roles
{
    /**
     * Crea los roles de usuario necesarios:
     * 
     * admin      =>      Tiene todos los permisos
     * usuario    =>      No tiene permisos
     *
     * @return void
     */
    public function createBasicRoles()
    {
        $user = new RoleModel();
        $user->name         = 'user';
        $user->display_name = 'Usuario';
        $user->description  = 'Usuario con permisos restringidos.';
        $user->save();
        
        $admin = new RoleModel();
        $admin->name         = 'admin';
        $admin->display_name = 'Administrador';
        $admin->description  = 'Usuario con permisos sobre la mayoría de las funciones del sistema.';
        $admin->save();
        
        // attach all permissios to admin role
        $permissions = PermissionModel::lists('id');
        $admin->perms()->sync($permissions);
    }
}