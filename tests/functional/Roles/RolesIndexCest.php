<?php
namespace Roles;

use \FunctionalTester;

use \sanoha\Models\User         as UserModel;
use \sanoha\Models\Role         as RoleModel;
use \sanoha\Models\Permission   as PermissionModel;

use \common\User                as UserCommons;
use \common\Permissions         as PermissionsCommons;
use \common\Roles               as RolesCommons;

class RolesIndexCest
{
    /**
     * The user commons actions
     *
     * @var \Users\_common\UserCommons
     */
    private $userCommons;

    /**
     * First instanciate the UserCommons to create user roles
     * and then login with the $userCommons->adminUser data
     *
     * @param \FunctionalTester $I
     * @return void
     */
    public function _before(FunctionalTester $I)
    {
        // creo los permisos para el módulo de roles
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createRolesModulePermissions();
        
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
        $roles = RoleModel::paginate(15);

        $I->amOnPage('/home');
        $I->click('Roles', 'a');
        $I->seeCurrentUrlEquals('/roles');
        
        $I->see('Roles', 'h1');
        
        $I->see($roles->first()->display_name, 'tbody tr:first-child td:nth-child(2)');
        $I->see($roles->first()->description, 'tbody tr:first-child td:nth-child(3)');
        
        $I->see($roles->last()->display_namename, 'tbody tr:last-child td:nth-child(2)');
        $I->see($roles->last()->description, 'tbody tr:last-child td:nth-child(3)');
    }
}