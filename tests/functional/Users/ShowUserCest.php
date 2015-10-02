<?php namespace Users;

use \FunctionalTester;
use \common\BaseTest;

class ShowUserCest
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

    // tests
    public function showUser(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('see user information in read mode, with no possibility to change data');

        $I->seeAuthentication();
        $testUser = \sanoha\Models\User::create($this->base_test->userCommons->testUser);
        $testUser->subCostCenters()->sync([1]);
        
        $I->seeRecord('users', [
            'name'  => $this->base_test->userCommons->testUser['name'],
            'lastname'  => $this->base_test->userCommons->testUser['lastname'],
            'email' => $this->base_test->userCommons->testUser['email']
            ]);
        
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'               =>  $testUser->id,
            'sub_cost_center_id'    =>  1
            ]);
        
        $I->amOnPage($this->base_test->userCommons->usersIndexUrl);
        $I->click($testUser->lastname. ' ' .$testUser->name, 'a');
        $I->seeCurrentUrlEquals('/users/'. $testUser->id);
        $I->see('Detalles de Usuario', 'h1');

        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->name]);
        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->lastname]);
        $I->seeElement('input[type="email"][disabled][readonly]', ['value' => $testUser->email]);
        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->getRoles()]);
        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->getSubCostCenters()]);
        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->getActivatedState()]);
        $I->see('Editar', 'a.btn-warning');
        $I->see('Mover a Papelera', 'button.btn-danger');
    }
}