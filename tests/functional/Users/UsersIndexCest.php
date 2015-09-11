<?php namespace Users;

use \FunctionalTester;
use \common\BaseTest;

class UsersIndexCest
{
    /**
     * The user commons actions
     *
     * @var \Users\_common\User
     */
    private $User;

    /**
     * First instanciate the User to:
     * - Create user roles
     * And then login with the $User->adminUser data
     *
     * @param \FunctionalTester $I
     * @return void
     */
    public function _before(FunctionalTester $I)
    {
        $this->base_test = new BaseTest;
        $this->base_test->users();

        $I->amLoggedAs($this->base_test->admin_user);
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

        $this->base_test->userCommons->createUsers(35);
        $users = \sanoha\Models\User::orderBy('updated_at', 'DES')
            ->where('email', '!=', $this->base_test->userCommons->adminUser['email'])
            ->paginate(15);

        $I->amOnPage('/users/');
        $I->see('Usuarios', 'h1');
        $I->see($users->first()->name, 'tbody tr:first-child td:nth-child(2)');
        $I->see($users->last()->name, 'tbody tr td:nth-child(2)');
        
        // the active user never must be seen
        $I->dontSee($this->base_test->userCommons->adminUser['name'], 'td');

        $I->click('2', 'a');
        $I->dontSee($this->base_test->userCommons->adminUser['name'], 'td');

        $I->click('3', 'a');
        $I->dontSee($this->base_test->userCommons->adminUser['name'], 'td');
    }

    /**
     * Check that there are no users registered
     */
    public function seeEmptyUsersList(FunctionalTester $I)
    {
        \DB::table('users')->delete();
        $I->am('admin user loged in');
        $I->wantTo('see what happens if there is no users on storage');

        $I->seeAuthentication();

        $I->amOnPage('/users/');
        $I->see('Usuarios', 'h1');
        $I->see('No hay usuarios en la base de datos.', '.alert-danger');
    }
}
