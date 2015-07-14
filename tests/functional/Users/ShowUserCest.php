<?php
namespace Users;

use \FunctionalTester;
use \Users\_common\UserCommons;
use \sanoha\Models\User;

class ShowUserCest
{
    /**
     * The test user data
     * 
     * @var array
     */ 
    private $testUser = [];

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

    // tests
    public function showUser(FunctionalTester $I)
    {
        $userCommons = new UserCommons;
        $I->am('admin user loged in');
        $I->wantTo('see user information in read mode, with no possibility to change data');

        $I->seeAuthentication();
        $testUser = User::create($this->testUser);
        $testUser->costCenter()->sync([1]);
        
        $I->seeRecord('users', [
            'name'  => $this->testUser['name'],
            'lastname'  => $this->testUser['lastname'],
            'email' => $this->testUser['email']
            ]);
        
        $I->seeRecord('cost_center_owner', [
            'user_id'           =>  $testUser->id,
            'cost_center_id'    =>  1
            ]);
        
        $I->amOnPage($userCommons->usersIndexUrl);
        $I->click($testUser->name, 'td a');
        $I->seeCurrentUrlEquals('/users/'. $testUser->id);
        $I->see('Detalles de Usuario', 'h1');

        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->name]);
        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->lastname]);
        $I->seeElement('input[type="email"][disabled][readonly]', ['value' => $testUser->email]);
        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->getRoles()]);
        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->getCostCenters()]);
        $I->seeElement('input[type="text"][disabled][readonly]', ['value' => $testUser->getActivatedState()]);
        $I->see('Editar', 'a.btn-warning');
        $I->see('Mover a Papelera', 'button.btn-danger');
    }
}