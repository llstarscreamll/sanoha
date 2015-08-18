<?php namespace Users;

use \FunctionalTester;

use \sanoha\Models\User     as UserModel;

use \common\User            as UserCommons;
use \common\Permissions     as PermissionsCommons;
use \common\SubCostCenters  as SubCostCentersCommons;
use \common\CostCenters     as CostCentersCommons;
use \common\Roles           as RolesCommons;

class ShowUserCest
{
    public function _before(FunctionalTester $I)
    {
        // creo sub centros de costo
        $this->costCentersCommons = new CostCentersCommons;
        $this->costCentersCommons->createCostCenters();
        
        // creo centros de costo
        $this->subCostCentersCommons = new SubCostCentersCommons;
        $this->subCostCentersCommons->createSubCostCenters();
        
        // creo los permisos para el módulo de usuarios
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createUsersModulePermissions();
        
        // creo los roles de usuario y añado todos los permisos al rol de administrador
        $this->rolesCommons = new RolesCommons;
        $this->rolesCommons->createBasicRoles();
        
        // creo el usuairo administrador
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();

        $I->amLoggedAs($this->userCommons->adminUser);
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
        $testUser = UserModel::create($this->userCommons->testUser);
        $testUser->subCostCenters()->sync([1]);
        
        $I->seeRecord('users', [
            'name'  => $this->userCommons->testUser['name'],
            'lastname'  => $this->userCommons->testUser['lastname'],
            'email' => $this->userCommons->testUser['email']
            ]);
        
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'               =>  $testUser->id,
            'sub_cost_center_id'    =>  1
            ]);
        
        $I->amOnPage($this->userCommons->usersIndexUrl);
        $I->click($testUser->name. ' ' .$testUser->lastname, 'a');
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