<?php namespace Roles;

use \FunctionalTester;
use \common\BaseTest;

class DeleteRoleCest
{
    /**
     * Role to test
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
     * Test soft delete one user role
     * 
     * @return void
     */ 
    public function softDeleteSingleRole(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('move to trash user role');

        $I->seeAuthentication();
        
        // create the test role
        $role = \sanoha\Models\Role::create($this->role);
        // attach some permissions to role
        $role->perms()->sync([1,2,3]); // have 6 permissions, only attach 3
        
        $I->amOnPage('/roles');
        $I->click($role->display_name, 'a');
        $I->seeCurrentUrlEquals('/roles/' . $role->id);
        $I->see('Detalles del Rol', 'h1');
        $I->click('Confirmar', '.btn-danger');
        
        $I->seeCurrentUrlEquals('/roles');
        $I->see('El rol ha sido movido a la papelera correctamente.', '.alert-success');
        $I->dontSee($role->display_name, 'a');
        $I->seeRecord('roles', ['name' => $this->role['name'], 'display_name' => $this->role['display_name']]);
        
    }
    
    /**
     * Delete several roles
     * 
     * @return void
     */
    public function softDeleteSeveralRoles(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('move to trash several user roles');

        $I->seeAuthentication();
        
        // create the test role
        $role = \sanoha\Models\Role::create($this->role);
        // attach some permissions to role
        $role->perms()->sync([1,2,3]); // have 6 permissions, only attach 3
        $roles = \sanoha\Models\Role::get();
        
        $I->amOnPage('/roles');
        
        $I->seeElement('input[type=checkbox]', ['value' => $roles->first()->id]);
        $I->seeElement('input[type=checkbox]', ['value' => $roles->last()->id]);
        
        $I->checkOption('#role-'.$roles->first()->id);
        $I->checkOption('#role-'.$roles->last()->id);
        
        $I->see('Mover Rol a la Papelera', 'button.btn-default span');
        $I->click('#btn-trash');
        
        $I->seeCurrentUrlEquals('/roles');
        $I->see('Los roles se han movido a la papelera correctamente.', '.alert-success div');
        
        // dont see roles trashed
        $I->dontSee($roles->first()->display_name, 'td a');
        $I->dontSee($roles->last()->display_name, 'td a');
        
        // but I still have roles on DB
        $I->seeRecord('roles', ['name' => $roles->first()->name, 'display_name' => $roles->first()->display_name]);
        $I->seeRecord('roles', ['name' => $roles->last()->name, 'display_name' => $roles->last()->display_name]);
    }
}