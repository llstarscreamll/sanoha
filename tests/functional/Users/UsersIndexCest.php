<?php
namespace Users;

use Users\_common\UserCommons;
use \Permissions\_common\PermissionCommons;

use \FunctionalTester;
use \sanoha\Models\User;

class UsersIndexCest
{
    /**
     * The user commons actions
     *
     * @var \Users\_common\UserCommons
     */
    private $userCommons;

    /**
     * First instanciate the UserCommons to:
     * - Create user roles
     * And then login with the $userCommons->adminUser data
     *
     * @param \FunctionalTester $I
     * @return void
     */
    public function _before(FunctionalTester $I)
    {
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();
        
        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Check the users registered list
     */
    public function userLists(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('see the registered users on a list');

        $I->seeAuthentication();

        $this->userCommons->haveUsers(35);
        $users = User::orderBy('updated_at', 'DES')
            ->where('email', '!=', $this->userCommons->adminUser['email'])
            ->paginate(15);

        $I->amOnPage('/users/');
        $I->see('Usuarios', 'h1');
        $I->see($users->first()->name, 'tbody tr:first-child td:nth-child(2)');
        $I->see($users->last()->name, 'tbody tr td:nth-child(2)');
        
        // the active user never must be seen
        $I->dontSee($this->userCommons->adminUser['name'], 'td');

        $I->click('2', 'a');
        $I->dontSee($this->userCommons->adminUser['name'], 'td');

        $I->click('3', 'a');
        $I->dontSee($this->userCommons->adminUser['name'], 'td');
    }

    /**
     * Check that there are no users registered
     */
    public function seeEmptyUsersList(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('see what happens if there is no users on storage');

        $I->seeAuthentication();

        $I->amOnPage('/users/');
        $I->see('Usuarios', 'h1');
        $I->see('No hay usuarios en la base de datos.', '.alert-danger');
    }
}
