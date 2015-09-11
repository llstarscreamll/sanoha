<?php namespace common;

use Carbon\Carbon;
use Faker\Factory as Faker;
use \sanoha\Models\Role as RoleModel;
use \sanoha\Models\Permission as PermissionModel;

class Roles
{
    private $faker;
    private $date;
    private $data = array();
    
    public function __construct()
    {
        $this->faker    = Faker::create();
        $this->date     = Carbon::now()->subMonth();
    }
    
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
        $this->data[] = [
            'name'           => 'user',
            'display_name'   => 'Usuario',
            'description'    => 'Usuario con permisos restringidos.',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'admin',
            'display_name'   => 'Administrador',
            'description'    => 'Usuario con permisos sobre la mayoría de las funciones del sistema.',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        \DB::table('roles')->insert($this->data);
        
        // attach all permissios to admin role
        $permissions = PermissionModel::lists('id');
        $admin = \sanoha\Models\Role::where('name', 'admin')->first();
        $admin->perms()->sync($permissions);
    }
}