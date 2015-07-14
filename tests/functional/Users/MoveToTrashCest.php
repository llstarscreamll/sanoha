<?php
namespace Users;

use \FunctionalTester;

use \Users\_common\UserCommons;
use \sanoha\Models\User;

class MoveToTrashCest
{
    private $userCommons;
    private $testUser;

    public function _before(FunctionalTester $I)
    {
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();
        
        $this->testUser = $this->userCommons->testUser;
        
        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Move to trash user on the user details page button
     */
    public function moveToTrashUserOnUserDetailsPageButton(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('move user to trash through the user details page button');

        $I->seeAuthentication();

        $user = User::create($this->testUser);

        $I->amOnPage($this->userCommons->usersIndexUrl);
        $I->see('Usuarios', 'h1');

        $I->see($this->testUser['name'], 'tr:first-child td:nth-child(2)');
        $I->see($this->testUser['email'], 'tr:first-child td:nth-child(4)');
        $I->click($this->testUser['name'], 'td a');
        
        $I->seeCurrentUrlEquals('/users/'. $user->id);
        $I->see('Detalles de Usuario', 'h1');
        $I->click('Confirmar', 'button.btn-danger');

        $I->seeCurrentUrlEquals('/users');
        $I->see('El usuario ha sido movido a la papelera.', '.alert-success');
        $I->seeRecord('users', [
            'name'      =>  $user->name,
            'lastname'  =>  $user->lastname,
            'email'     =>  $user->email,
            ]);
    }
}
