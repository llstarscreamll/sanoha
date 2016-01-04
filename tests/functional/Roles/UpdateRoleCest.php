<?php
namespace Roles;

use \FunctionalTester;
use \common\BaseTest;

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
        $this->base_test = new BaseTest;
        $this->base_test->roles();

        $I->amLoggedAs($this->base_test->admin_user);
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
        $role = \sanoha\Models\Role::create($this->role);
        // attach some permissions to role
        $role->perms()->sync([1, 2, 3]); // have 6 permissions, only attach 3
        // get all permissions
        $permissions = \sanoha\Models\Permission::all();
        // get the role permissions
        $rolePermissions = \sanoha\Models\Role::find($role->id)->perms()->orderBy('name', 'asc')->get();

        $I->amOnPage('/roles');
        $I->click($role->display_name, 'a');
        $I->seeCurrentUrlEquals('/roles/' . $role->id);

        // la página de detalles del rol
        $I->see('Detalles del Rol', 'h1');
        $I->click('Editar', 'a');
        
        // página de edición de info de rol
        $I->seeCurrentUrlEquals('/roles/' . $role->id . '/edit');
        $I->see('Editar Rol', 'h1');
        
        // check basic role info
        $I->seeInFormFields('form', [
            'name'          =>  $role->name,
            'display_name'  =>  $role->display_name,
            'description'   =>  $role->description
            ]);
            
        // I see all the permissions listed
        foreach ($permissions as $permission) {
            $I->seeElement('input', ['value' => $permission->id]);
            $I->dontSeeElement('input', ['value' => $permission->id, 'disabled']);
        }
            
        // I see the role permissions checked
        foreach ($rolePermissions as $rolePermission) {
            $I->seeElement('input[type=checkbox]:checked', ['value' => $rolePermission->id]);
        }
        
        // los siguientes elementos no están asociados al rol
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
                1,
                2,
                3,
                4,
                5,
                6,
                ]
            ], 'Actualizar');
        
        $I->seeCurrentUrlEquals('/roles');
        
        $I->see('Roles', 'h1');
        $I->see('El rol ha sido actualizado correctamente.', '.alert-success div');
        $I->see('Permisos de rol actualizados correctamente.', '.alert-success div');
        $I->see($role['display_name'], 'tbody tr:first-child td:nth-child(2) a');
        $I->see($role['description'], 'tbody tr:first-child td:nth-child(3)');
        
        // get the updated role permissions
        $rolePermissions = \sanoha\Models\Role::find($role->id)->perms()->orderBy('name', 'asc')->get();
        
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
        foreach ($rolePermissions as $rolePermission) {
            $I->seeElement('input[type=checkbox]:checked', ['value' => $rolePermission->id]);
        }

        // veo los cambios en la base de datos
        $I->seeRecord('permission_role', [
            'permission_id' =>  1,
            'role_id'       =>  $role->id
        ]);

        $I->seeRecord('permission_role', [
            'permission_id' =>  2,
            'role_id'       =>  $role->id
        ]);

        $I->seeRecord('permission_role', [
            'permission_id' =>  3,
            'role_id'       =>  $role->id
        ]);

        $I->seeRecord('permission_role', [
            'permission_id' =>  4,
            'role_id'       =>  $role->id
        ]);

        $I->seeRecord('permission_role', [
            'permission_id' =>  5,
            'role_id'       =>  $role->id
        ]);
    }
}
