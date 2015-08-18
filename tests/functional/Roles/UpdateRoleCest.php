<?php namespace Roles;

use \FunctionalTester;

use \sanoha\Models\User         as UserModel;
use \sanoha\Models\Role         as RoleModel;
use \sanoha\Models\Permission   as PermissionModel;

use \common\User                as UserCommons;
use \common\Permissions         as PermissionsCommons;
use \common\Roles               as RolesCommons;

class UpdateRoleCest
{
    /**
     * The user commons actions
     *
     * @var \Users\_common\UserCommons
     */
    private $userCommons;

    /**
     * Role to test
     * 
     * @var array
     */
    private $role = [
        'name'          =>  'test',
        'display_name'  =>  'Test Role',
        'description'   =>  'Role for testing purposes'
        ];

    /**
     * Instanciate the UserCommons to create user roles and then login with
     * the $userCommons->adminUser data.
     * Instanciate the PermissionsCommons and RoleFormRequest for get
     * validation messages
     *
     * @param \FunctionalTester $I
     * @return void
     */
    public function _before(FunctionalTester $I)
    {
        // creo los permisos para el módulo de roles
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createRolesModulePermissions();
        
        // creo los roles de usuario y añado todos los permisos al rol de administrador
        $this->rolesCommons = new RolesCommons;
        $this->rolesCommons->createBasicRoles();
        
        // creo el usuairo administrador
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();

        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Test to update role
     * 
     * @return void
     */
    public function updateRole(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('update role stored on database');

        $I->seeAuthentication();
        
        // create the test role
        $role = RoleModel::create($this->role);
        // attach some permissions to role
        $role->perms()->sync([1,2,3]); // have 6 permissions, only attach 3
        // get all permissions
        $permissions = PermissionModel::select('name')->get();
        // get the role permissions
        $rolePermissions = RoleModel::find($role->id)->perms()->orderBy('name', 'asc')->get();

        $I->amOnPage('/roles');
        $I->click($role->display_name, 'a');
        $I->seeCurrentUrlEquals('/roles/' . $role->id);
        $I->see('Detalles del Rol', 'h1');
        $I->click('Editar', 'a');
        
        $I->seeCurrentUrlEquals('/roles/' . $role->id . '/edit');
        $I->see('Editar Rol', 'h1');
        
        // check basic role info
        $I->seeInFormFields('form', [
            'name'          =>  $role->name,
            'display_name'  =>  $role->display_name,
            'description'   =>  $role->description
            ]);
            
        // I see all the permissions listed
        foreach($permissions as $permission)
            $I->seeElement('input', ['value' => $permission->name]);
            
        // I see the role permissions checked
        foreach($rolePermissions as $rolePermission)
            $I->seeElement('input[type=checkbox]:checked', ['value' => $rolePermission->name]);
        
        // check the unattached roles that aren´t checked
        $I->dontSeeElement('input[type=checkbox]:checked', ['value' => 'user.list']); // role 4 unattached
        $I->dontSeeElement('input[type=checkbox]:checked', ['value' => 'user.create']); // role 5 unattached
        $I->dontSeeElement('input[type=checkbox]:checked', ['value' => 'user.edit']); // role 6 unattached
        
        // new role data
        $role->name = 'updated.role';
        $role->display_name = 'Updated Role';
        $role->description = 'New description for updated role';
        
        // delaying test, for the updated_at column update...
        sleep(1);
        
        $I->submitForm('form', [
            'name'          =>  $role->name,
            'display_name'  =>  $role->display_name,
            'description'   =>  $role->description,
            'permissions'   =>  [
                'role.list',
                'role.create',
                'role.update',
                'user.list',
                'user.create',
                'user.edit',
                ]
            ], 'Actualizar');
        
        $I->seeCurrentUrlEquals('/roles');
        
        $I->see('Roles', 'h1');
        $I->see('El rol ha sido actualizado correctamente.', '.alert-success div');
        $I->see('Permisos de rol actualizados correctamente.', '.alert-success div');
        $I->see($role['display_name'], 'tbody tr:first-child td:nth-child(2) a');
        $I->see($role['description'], 'tbody tr:first-child td:nth-child(3)');
        
        // get the updated role permissions
        $rolePermissions = RoleModel::find($role->id)->perms()->orderBy('name', 'asc')->get();
        
        $I->click($role->display_name, 'a');
        $I->seeCurrentUrlEquals('/roles/' . $role->id);
        $I->see('Detalles del Rol', 'h1');
        $I->click('Editar', 'a');
        
        $I->seeCurrentUrlEquals('/roles/' . $role->id . '/edit');
        $I->see('Editar Rol', 'h1');
        
        // check the updated basic role info
        $I->seeInFormFields('form', [
            'name'          =>  $role->name,
            'display_name'  =>  $role->display_name,
            'description'   =>  $role->description
            ]);
        
        // I see the updated role permissions checked
        foreach($rolePermissions as $rolePermission)
            $I->seeElement('input[type=checkbox]:checked', ['value' => $rolePermission->name]);
        
    }
}