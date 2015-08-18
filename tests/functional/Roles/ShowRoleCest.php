<?php namespace Roles;

use \FunctionalTester;

use \sanoha\Models\User         as UserModel;
use \sanoha\Models\Role         as RoleModel;
use \sanoha\Models\Permission   as PermissionModel;

use \common\User                as UserCommons;
use \common\Permissions         as PermissionsCommons;
use \common\Roles               as RolesCommons;

use \sanoha\Http\Requests\RoleFormRequest;

class ShowRoleCest
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
     * Test details role view
     */ 
    public function tryToTest(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('see details role stored on database');

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
        
        // check basic role info
        $I->seeInFormFields('form', [
            'name'          =>  $role->name,
            'display_name'  =>  $role->display_name,
            'description'   =>  $role->description
        ]);
        
        // check that all input have disabled attribute
        $I->seeElement('input[name=name]:disabled');
        $I->seeElement('input[name=display_name]:disabled');
        $I->seeElement('textarea[name=description]:disabled');
        
        // I see the role permissions checked and them have disabled attribute
        foreach($rolePermissions as $rolePermission)
            $I->seeElement('input:disabled[type=checkbox]:checked', ['value' => $rolePermission->name]);
    }
}