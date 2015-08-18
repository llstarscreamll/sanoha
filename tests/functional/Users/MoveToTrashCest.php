<?php namespace Users;

use \FunctionalTester;

use \sanoha\Models\User     as UserModel;

use \common\User           as UserCommons;
use \common\Permissions    as PermissionsCommons;
use \common\Roles          as RolesCommons;

class MoveToTrashCest
{
    public function _before(FunctionalTester $I)
    {
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

    /**
     * Move to trash user on the user details page button
     */
    public function moveToTrashUserOnUserDetailsPageButton(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('move user to trash through the user details page button');

        $I->seeAuthentication();

        $user = UserModel::create($this->userCommons->testUser);

        $I->amOnPage($this->userCommons->usersIndexUrl);
        $I->see('Usuarios', 'h1');

        $I->see($this->userCommons->testUser['name'], 'tr:first-child td:nth-child(2)');
        $I->see($this->userCommons->testUser['email'], 'tr:first-child td:nth-child(4)');
        $I->click($this->userCommons->testUser['name'], 'td a');
        
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
