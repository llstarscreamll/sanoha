<?php namespace Users;

use \FunctionalTester;

use \sanoha\Models\User     as UserModel;

use \common\User            as UserCommons;
use \common\Permissions     as PermissionsCommons;
use \common\Roles           as RolesCommons;

class CreateUserCest
{
    /**
     * The user create url
     * 
     * @var string
     */ 
    private $create_url = '/users/create';
    
    private $rolesCommons;

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
     * Pruebo los mensajes de error al crea un nuevo usuario
     */
    public function TestFormErrors(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('create user with invalid data to check errors');

        $I->seeAuthentication();

        $I->amOnPage($this->create_url);
        $I->see('Crear Usuario', 'h1');

        $I->submitForm('form', [
            'name'              => '',
            'lastname'          => '',
            'email'             => '',
            'role_id'           => [],
            'activated'         => true,
            'password'          => '',
            'password_confirmation'   => ''
        ], 'Crear');

        $I->seeCurrentUrlEquals($this->create_url);

        $I->seeFormHasErrors();
        $I->seeFormErrorMessage('name', 'El nombre es un campo obligatorio.');
        $I->seeFormErrorMessage('name', 'El nombre es un campo obligatorio.');
        $I->seeFormErrorMessage('email', 'La dirección de correo electrónico es oblogatoria.');
        $I->seeFormErrorMessage('password', 'Por favor digita una contraseña.');

        $I->submitForm('form', [
            'name'              => 'Ed',
            'lastname'          => 'Ra',
            'email'             => 'edy.ramon',
            'role_id'           => 'root',
            'activated'         => true,
            'password'          => 'edy',
            'password_confirmation'   => 'Edy'
        ], 'Crear');

        $I->seeCurrentUrlEquals($this->create_url);

        $I->seeFormHasErrors();
        $I->seeFormErrorMessage('name', 'El nombre debe tener al menos 3 caracteres.');
        $I->seeFormErrorMessage('lastname', 'El apellido debe tener al menos 3 caracteres.');
        $I->seeFormErrorMessage('email', 'La dirección de correo electrónico no es válida.');
        $I->seeFormErrorMessage('role_id', 'Tipo de usuario inválido.');
        $I->seeFormErrorMessage('password', 'Las contraseñas no coinciden.');


        $I->submitForm('form', [
            'name'              => 'Eeeeeeeeeeeeeeeeeeddddddddddddddddddddddddddddddddddyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy',
            'lastname'          => 'Raaaaaaaaaaaaaaaaammmmmmmmmmmmmmmmmmmooooooooooooooooooooooonnnnnnnnnnnnnnnnnnnn',
            'email'             => 'travis.orbin@example.com', // the same as admin, for unique validation
            'role_id'           => 1,
            'activated'         => false,
            'password'          => 'edy',
            'password_confirmation'   => 'edy'
        ], 'Crear');

        $I->seeCurrentUrlEquals($this->create_url);
        $I->seeFormHasErrors();
        $I->seeFormErrorMessage('name', 'El nombre debe tener máximo 50 caracteres.');
        $I->seeFormErrorMessage('lastname', 'El apellido debe tener máximo 50 caracteres.');
        $I->seeFormErrorMessage('email', 'La dirección de correo ya ha sido registrada.');
        $I->seeFormErrorMessage('password', 'La contraseña debe tener al menos 6 caracteres.');

        $I->submitForm('form', [
            'name'              => '#Edy',
            'lastname'          => '@Ramon',
            'email'             => 'travis.orbin@example.com', // the same as admin, for unique validation
            'role_id'           => 1,
            'activated'         => false,
            'password'          => 'edy.ramon',
            'password_confirmation'   => 'edy.ramon'
        ], 'Crear');

        $I->seeCurrentUrlEquals($this->create_url);
        $I->seeFormHasErrors();
        $I->seeFormErrorMessages([
            'name'      =>  'El nombre sólo puede contener letras y/o espacios.',
            'lastname'  =>  'El apellido sólo puede contener letras y/o espacios.',
            'email'     =>  'La dirección de correo ya ha sido registrada.',
        ]);
    }

    /**
     * Create user with valid data
     */
    public function createUser(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('create user');

        $I->seeAuthentication();

        $I->amOnPage('/users');
        $I->click('#create-user-link');
        $I->seeCurrentUrlEquals($this->create_url);
        $I->see('Crear Usuario', 'h1');

        $newUser = [
            'name'              => 'Eddy',
            'lastname'          => 'Ramon',
            'email'             => 'edy.ramon@example.com',
            'role_id'           => 1,
            'activated'         => 1,
            'subCostCenter_id'     => [1,2],
            'password'          => 'edy.ramon',
            'password_confirmation'   => 'edy.ramon'
        ];
        
        //$I->checkOption('activated');
        //$I->selectOption('form #role_id', 1);
        //$I->selectOption('form #costCenter_id', 1);
        
        $I->submitForm('form', $newUser, 'Crear');
        
        $I->seeRecord('users', [
            'name'              => $newUser['name'],
            'lastname'          => $newUser['lastname'],
            'email'             => $newUser['email'],
            'activated'         => $newUser['activated']
        ]);
        
        $userRecord = UserModel::where('email', '=', $newUser['email'])->get()->first();
        
        //dd($userRecord->id);
        
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'           =>  $userRecord->id,
            'sub_cost_center_id'    =>  1
        ]);
        
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'           =>  $userRecord->id,
            'sub_cost_center_id'    =>  2
        ]);

        $I->seeCurrentUrlEquals('/users');
        $I->see('Usuario creado correctamente.', '.alert-success div');
        $I->see('Se ha añadido el rol al usuario correctamente.', '.alert-success div');
        $I->see('Asignación de centro de costos exitosa.', '.alert-success div');
        $I->see($newUser['name'], 'tbody tr:first-child td:nth-child(2)');
        $I->see($newUser['email'], 'tbody tr:first-child td:nth-child(4)');
        $I->see('Activado', 'tbody tr:first-child td:nth-child(5)'); // activated user

    }
}