<?php namespace Users;

use \FunctionalTester;
use \common\BaseTest;

class MoveToTrashCest
{
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
     * Move to trash user on the user details page button
     */
    public function moveToTrashUserOnUserDetailsPageButton(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('move user to trash through the user details page button');

        $I->seeAuthentication();

        $user = \sanoha\Models\User::create($this->base_test->userCommons->testUser);

        $I->amOnPage($this->base_test->userCommons->usersIndexUrl);
        $I->see('Usuarios', 'h1');

        $I->see($this->base_test->userCommons->testUser['name'], 'tr:first-child td:nth-child(2)');
        $I->see($this->base_test->userCommons->testUser['email'], 'tr:first-child td:nth-child(4)');
        $I->click($this->base_test->userCommons->testUser['name'], 'td a');
        
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
