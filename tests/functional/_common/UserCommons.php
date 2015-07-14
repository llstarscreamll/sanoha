<?php namespace Users\_common;

use Faker\Factory as Faker;
use \Permissions\_common\PermissionCommons;
use \Permissions\_common\SystemCommons;
use \sanoha\Models\Role;
use \sanoha\Models\User;

class UserCommons
{
    /**
     * The user admin data, this user is the actor
     * in all scenarios in admin role contexts
     *
     * @var array
     */
    public $adminUser = [
        'name'          =>    'Travis',
        'lastname'      =>    'Orbin',
        'email'         =>    'travis.orbin@example.com',
        'password'      =>    '123456'
    ];

    /**
     * The test user info, this is the user to test
     * functionalities on users module
     *
     * @var array
     */
    public $testUser = [
        'role_id'       =>    1,
        'costCenter_id' =>    [1],
        'name'          =>    'Andrew',
        'lastname'      =>    'Mars',
        'email'         =>    'andrew.mars@example.com',
        'password'      =>    '123456'
    ];

    /**
     * The base admin url
     *
     * @var string
     */
    public $usersIndexUrl = '/users';
    
    /**
     * Create Cost Centers
     */ 
    public function haveCostCenters()
    {
        $costCenters = new SystemCommons;
        $costCenters->createCostCenters();
    }
    
    /**
     * Create the admin user and attach role
     * 
     * @return void
     */
    public function createAdminUser()
    {
        // create permissions and roles for the admin user
        $this->createPermissinsAndRoles();
        
        // create cost centers
        $this->haveCostCenters();
        
        $user = User::firstOrCreate($this->adminUser);
        $admin_role = \sanoha\Models\Role::find(2); // 2 is the admin role id
        $user->attachRole($admin_role);
        $user->costCenter()->sync([1,2]);
        
        return $user;
    }
    
    /**
     * Create the permissions and roles for users
     * 
     * @return void
     */
    public function createPermissinsAndRoles()
    {
        $this->permissionCommons = new PermissionCommons;
        $this->permissionCommons->havePermissions();
        $this->haveUserRoles();
    }
    

    /**
     * Create several users on storage, default 10 users
     *
     * @param int $num
     * @return void
     */
    public function haveUsers($num = 10)
    {
        $faker = Faker::create();

        for ($i=0; $i < $num; $i++) {
            $data[] = [
                'name'          =>    $faker->firstName,
                'lastname'      =>    $faker->lastname,
                'email'         =>    $faker->unique()->email,
                'password'      =>    '123456',
                'created_at'    =>  date('Y-m-d H:i:s'),
                'updated_at'    =>  date('Y-m-d H:i:s'),
                'deleted_at'    =>  null
            ];
        }
        
        \DB::table('users')->insert($data);
    }
    
    /**
     * Create several employees on storage, default 10 users
     *
     * @param int $num
     * @return void
     */
    public function haveEmployees($num = 10)
    {
        $faker = Faker::create();
        $data = [];
        
        \sanoha\Models\Position::create([
            'name'  =>  'Minero'
            ]);
        
        $data[] = [
                'position_id'           =>    '1', // cargo Minero
                'cost_center_id'        =>    '1', // Projecto Beteitiva, creado en SystemCommons
                'name'                  =>    'Anselmo',
                'lastname'              =>    'Díaz',
                'identification_number' => '123456789',
                'created_at'            =>  date('Y-m-d H:i:s'),
                'updated_at'            =>  date('Y-m-d H:i:s'),
                'deleted_at'            =>  null
            ];
        
        $data[] = [
                'position_id'           =>    '1', // cargo Minero
                'cost_center_id'        =>    '2', // Projecto Sanoha, creado en SystemCommons
                'name'                  =>    'Enrique',
                'lastname'              =>    'Carriaso',
                'identification_number' => '2515487',
                'created_at'            =>  date('Y-m-d H:i:s'),
                'updated_at'            =>  date('Y-m-d H:i:s'),
                'deleted_at'            =>  null
            ];

        for ($i=0; $i < $num; $i++) {
            $data[] = [
            'position_id'               =>      '1', // miniworker
            'cost_center_id'            =>      $faker->numberBetween(1,2), // los centro de costo fueron creados en SystemCommons
            'name'                      =>      $faker->firstName,
            'lastname'                  =>      $faker->lastname,
            'identification_number'     =>      '123456789',
            'created_at'                =>  date('Y-m-d H:i:s'),
            'updated_at'                =>  date('Y-m-d H:i:s'),
            'deleted_at'                =>  null
            ];
        }
        
        \DB::table('employees')->delete();
        \DB::table('employees')->insert($data);
    }
    
    /**
     * 
     */
    public function haveMiningActivities()
    {
        $data = [];
        
        // en orden alfabético    
        $data[] = [
            'name'          =>  'Avance Roca',
            'short_name'    =>  'AR',
            'maximum'       =>  10,
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
            ];
            
        $data[] = [
            'name'          =>  'Embasado',
            'short_name'    =>  'E',
            'maximum'       =>  5,
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
            ];
            
        $data[] = [
            'name'          =>  'Malacate',
            'short_name'    =>  'M',
            'maximum'       =>  4,
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
            ];
        
        $data[] = [
            'name'          =>  'Picado',
            'short_name'    =>  'P',
            'maximum'       =>  3,
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
            ];
        
        \DB::table('mining_activities')->insert($data);
    }

    /**
     * Create user roles on storage, only user and admin roles
     *
     * @return void
     */
    public function haveUserRoles()
    {
        $user = new Role();
        $user->name         = 'user';
        $user->display_name = 'Usuario';
        $user->description  = 'Usuario con permisos restringidos.';
        $user->save();
        
        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Administrador';
        $admin->description  = 'Usuario con permisos sobre la mayoría de las funciones del sistema.';
        $admin->save();
        
        // attach all permissios to admin role
        $permissions = \sanoha\Models\Permission::lists('id');
        $admin->perms()->sync($permissions);
    }
}