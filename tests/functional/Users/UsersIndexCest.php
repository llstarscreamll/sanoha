<?php namespace Users;

use \FunctionalTester;

use \sanoha\Models\User     as UserModel;

use \common\User           as UserCommons;
use \common\Permissions    as PermissionsCommons;
use \common\Roles          as RolesCommons;

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
     * Check the users registered list
     */
    public function userLists(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('see the registered users on a list');

        $I->seeAuthentication();

        $this->userCommons->createUsers(35);
        $users = UserModel::orderBy('updated_at', 'DES')
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
