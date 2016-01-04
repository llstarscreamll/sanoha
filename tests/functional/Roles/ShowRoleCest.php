<?php
namespace Roles;

use \FunctionalTester;
use \common\BaseTest;

class ShowRoleCest
{
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
        $this->base_test = new BaseTest;
        $this->base_test->roles();

        $I->amLoggedAs($this->base_test->admin_user);
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
        $role = \sanoha\Models\Role::create($this->role);
        // attach some permissions to role
        $role->perms()->sync([1, 2, 3]); // have 6 permissions, only attach 3
        // get all permissions
        $permissions = \sanoha\Models\Permission::select('name')->get();
        // get the role permissions
        $rolePermissions = \sanoha\Models\Role::find($role->id)->perms()->orderBy('name', 'asc')->get();

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
        foreach ($rolePermissions as $rolePermission) {
            $I->seeElement('input:disabled[type=checkbox]:checked', ['value' => $rolePermission->id]);
        }
    }
}
