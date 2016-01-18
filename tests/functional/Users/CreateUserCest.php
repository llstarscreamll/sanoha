<?php
namespace Users;

use \FunctionalTester;
use \common\BaseTest;

class CreateUserCest
{
    /**
     * The user create url
     * 
     * @var string
     */
    private $create_url = '/users/create';
    
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
     * Prueba la creación de un usuario sin especificar centro de costo, o cargo, o rol, etc...
     * es decir con las relacines vacías...
     */
    public function createWithEmptyRelations(FunctionalTester $I)
    {
        $I->am('un administrador del sistema');
        $I->wantTo('comprobar que no hay errores si omito algunos campos en creacion');

        // estoy en la página de edición de usuario, modifico mi propio usuario
        $I->amOnPage('/users/create');

        // envío el formulario con el campo de contraseña vacío, no se debe actualizar la contraseña
        $I->submitForm('form', [
            'role_id'               =>  [],
            'sub_cost_center_id'    =>  [],
            'area_id'               =>  '',
            'employee_id'           =>  [],
            'name'                  =>  'Andrew Lorens',
            'lastname'              =>  'Mars Coleman',
            'activated'             =>  1,
            'email'                 =>  'andrew.mc@example.com',
            'password'              =>  '123456789',
            'password_confirmation' =>  '123456789'
        ]);

        // soy redirigido a la página de
        $I->seeCurrentUrlEquals('/users');
        // veo mensaje de exito en la operación
        $I->see('Usuario creado correctamente.', '.alert-success div');
        $I->dontSee('Asignación de centro de costos exitosa.', '.alert-success div');
        $I->dontSee('Se ha añadido el rol al usuario correctamente.', '.alert-success div');
        $I->dontSee('Asignación de empleado(s) exitosa.', '.alert-success div');
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
            'employee_id'       => [21252, 5654],
            'sub_cost_center_id'=>  1,
            'password'          => '',
            'password_confirmation'   => ''
        ], 'Crear');

        $I->seeCurrentUrlEquals($this->create_url);

        $I->seeFormHasErrors();
        $I->seeFormErrorMessage('name', 'El nombre es un campo obligatorio.');
        $I->seeFormErrorMessage('name', 'El nombre es un campo obligatorio.');
        $I->seeFormErrorMessage('email', 'La dirección de correo electrónico es oblogatoria.');
        $I->seeFormErrorMessage('password', 'Por favor digita una contraseña.');
        $I->seeFormErrorMessage('employee_id', 'No se encontró información del empleado.');
        $I->seeFormErrorMessage('sub_cost_center_id', 'El subcentro de costo debe ser un conjunto.');

        $I->submitForm('form', [
            'name'              => 'Ed',
            'lastname'          => 'Ra',
            'email'             => 'edy.ramon',
            'role_id'           => ['root'],
            'employee_id'       =>  1,
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
        $I->seeFormErrorMessage('employee_id', 'El empleado debe ser un conjunto.');


        $I->submitForm('form', [
            'name'              => 'Eeeeeeeeeeeeeeeeeeddddddddddddddddddddddddddddddddddyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy',
            'lastname'          => 'Raaaaaaaaaaaaaaaaammmmmmmmmmmmmmmmmmmooooooooooooooooooooooonnnnnnnnnnnnnnnnnnnn',
            'email'             => 'travis.orbin@example.com', // the same as admin, for unique validation
            'role_id'           => [1],
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
            'role_id'   =>  'El tipo de usuario debe ser un conjunto.'
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
            'name'                  => 'Eddy',
            'lastname'              => 'Ramon',
            'email'                 => 'edy.ramon@example.com',
            'role_id'               => [1],
            'activated'             => 1,
            'sub_cost_center_id'    => [1,2],
            'employee_id'           => [1,2],
            'area_id'               => 1,
            'area_chief'            => true,
            'password'              => 'edy.ramon',
            'password_confirmation' => 'edy.ramon'
        ];
        
        $I->submitForm('form', $newUser, 'Crear');
        
        $I->seeCurrentUrlEquals('/users');
        $I->see('Usuario creado correctamente.', '.alert-success div');
        $I->see('Se ha añadido el rol al usuario correctamente.', '.alert-success div');
        $I->see('Asignación de centro de costos exitosa.', '.alert-success div');
        $I->see('Asignación de empleado(s) exitosa.', '.alert-success div');
        $I->see($newUser['name'], 'tbody tr:first-child td:nth-child(2)');
        $I->see($newUser['email'], 'tbody tr:first-child td:nth-child(4)');
        $I->see('Activado', 'tbody tr:first-child td:nth-child(5)'); // activated user

        $userRecord = \sanoha\Models\User::where('email', '=', $newUser['email'])->get()->first();

        $I->seeRecord('users', [
            'name'                  => 'Eddy',
            'lastname'              => 'Ramon',
            'email'                 => 'edy.ramon@example.com',
            'activated'             => 1,
            'area_id'               => 1,
            'area_chief'            => 1,
        ]);
        // veo los subcentros de costo asociados
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'           =>  $userRecord->id,
            'sub_cost_center_id'=>  1
        ]);
        
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'           =>  $userRecord->id,
            'sub_cost_center_id'=>  2
        ]);
        
        // veo los empleados asociados
        $I->seeRecord('employee_owners', [
            'user_id'           =>  $userRecord->id,
            'employee_id'       =>  1
        ]);
        
        $I->seeRecord('employee_owners', [
            'user_id'           =>  $userRecord->id,
            'employee_id'       =>  2
        ]);
    }
}
