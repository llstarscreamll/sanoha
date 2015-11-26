<?php
namespace Roles;

use \FunctionalTester;
use \common\BaseTest;

class RolesIndexCest
{
    /**
     * First instanciate the UserCommons to create user roles
     * and then login with the $userCommons->adminUser data
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
     * Test the get roles index functionality
     *
     * @param \FunctionalTester $I
     * @return void
     */
    public function rolesIndex(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('check the roles index information');

        $I->seeAuthentication();
        $roles = \sanoha\Models\Role::paginate(15);

        $I->amOnPage('/home');
        $I->click('Roles', 'a');
        $I->seeCurrentUrlEquals('/roles');
        
        $I->see('Roles', 'h1');
        
        $I->see($roles->first()->display_name, 'tbody tr:last-child td:nth-child(2)');
        $I->see($roles->first()->description, 'tbody tr:last-child td:nth-child(3)');
        
        $I->see($roles->last()->display_namename, 'tbody tr:first-child td:nth-child(2)');
        $I->see($roles->last()->description, 'tbody tr:first-child td:nth-child(3)');
    }
}
