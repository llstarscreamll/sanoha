<?php namespace Users;

use \FunctionalTester;
use \common\BaseTest;

class UpdateUserCest
{
    /**
     * The user commons actions
     *
     * @var \common\User
     */
    private $userCommons;

    /**
     * First instanciate the User to:
     * - Create user roles
     * - Get the test user
     * And then login with the $User->adminUser data
     *
     * @param \FunctionalTester $I
     * @return void
     */
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
     * Test the update user information functionality with valid data
     *
     * @param \FunctionalTester $I
     * @return void
     */
    public function updateUser(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('update user info');

        $I->seeAuthentication();

        $user = \sanoha\Models\User::create($this->base_test->userCommons->testUser);
        $user->subCostCenters()->sync($this->base_test->userCommons->testUser['subCostCenter_id']);

        $I->amOnPage($this->base_test->userCommons->usersIndexUrl);
        $I->see('Usuarios', 'h1');

        $I->see($this->base_test->userCommons->testUser['name'], 'tr:first-child td:nth-child(2)');
        $I->see($this->base_test->userCommons->testUser['email'], 'tr:first-child td:nth-child(4)');
        $I->see($user->getActivatedState(), 'tr:first-child td:nth-child(5)');
        $I->click($this->base_test->userCommons->testUser['name'], 'td a');
        
        $I->seeCurrentUrlEquals('/users/'. $user->id);
        $I->see('Detalles de Usuario', 'h1');
        $I->click('Editar', 'a.btn-warning');

        $I->seeCurrentUrlEquals('/users/'. $user->id .'/edit');
        $I->see('Actualizar Usuario', 'h1');
        
        $this->base_test->userCommons->testUser = ['password' => ''];

        $I->seeInFormFields('form', $this->base_test->userCommons->testUser);
        
        // nuevo requerimineto, debo ver la lista de centros de costo con los subcentros
        // anidados en el select
        $I->seeElement('select optgroup', ['label' => 'Proyecto Beteitiva']);
        $I->see('Bocamina 1', 'select optgroup option');
        $I->see('Bocamina 2', 'select optgroup option');
        
        $I->dontSeeCheckboxIsChecked('activated');

        // new user data
        $this->base_test->userCommons->testUser = [
            'role_id'       =>    [1,2],
            'subCostCenter_id' =>    [1,2],
            'name'          =>    'Andrew Lorens',
            'lastname'      =>    'Mars Coleman',
            'activated'     =>    1,
            'email'         =>    'andrew.45698@gmail.com',
            'password'      =>    '654321',
            'password_confirmation'      =>    '654321'
        ];

        $I->submitForm('form', $this->base_test->userCommons->testUser, 'Actualizar');
        
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'           =>  $user->id,
            'sub_cost_center_id'    =>  1
            ]);
            
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'           =>  $user->id,
            'sub_cost_center_id'    =>  2
            ]);

        $I->seeCurrentUrlEquals($this->base_test->userCommons->usersIndexUrl);
        $I->see('Usuarios', 'h1');
        $I->see('Usuario actualizado correctamente.', '.alert-success');
        $I->see('Se ha actualizado el rol del usuario correctamente.', '.alert-success');
        $I->see('Actualización de centro de costos exitosa.', '.alert-success');
        
        /**
         * update user again after asign cost center
         */
         $I->click($this->base_test->userCommons->testUser['name'], 'td a');
        
        $I->seeCurrentUrlEquals('/users/'. $user->id);
        $I->see('Detalles de Usuario', 'h1');
        $I->click('Editar', 'a.btn-warning');

        $I->seeCurrentUrlEquals('/users/'. $user->id .'/edit');
        $I->see('Actualizar Usuario', 'h1');
        
        $this->base_test->userCommons->testUser = [
            'role_id[]'         =>    [1,2],
            'subCostCenter_id[]'   =>    [],
            'name'              =>    'Andrew Lorens',
            'lastname'          =>    'Mars Coleman',
            'email'             =>    'andrew.45698@gmail.com',
            'activated'         =>    1,
            'password'          =>    '',
            'password_confirmation'=>    ''
        ];
        
        $I->seeInFormFields('form', $this->base_test->userCommons->testUser);
        
        $I->see('', 'input[type=checkbox][name=activated]');
        $I->seeCheckboxIsChecked('form input[type=checkbox]');

        // new user data
        $this->base_test->userCommons->testUser = [
            'role_id'       =>    [1,2],
            'subCostCenter_id' =>    [],
            'name'          =>    'Andrew Lorens',
            'lastname'      =>    'Mars Coleman',
            'email'         =>    'andrew.45698@gmail.com',
            'activated'     =>    false,
            'password'      =>    '654321',
            'password_confirmation'      =>    '654321'
        ];
        
        $I->submitForm('form', $this->base_test->userCommons->testUser, 'Actualizar');
        
        $I->dontSeeCheckboxIsChecked('#activated');
        
        $I->seeRecord('users', [
            'email'     =>  'andrew.45698@gmail.com',
            'activated' =>  false
            ]);
        
        $I->dontSeeRecord('sub_cost_center_owner', [
            'user_id'           =>  $user->id,
            'sub_cost_center_id'    =>  1
            ]);
            
        $I->dontSeeRecord('sub_cost_center_owner', [
            'user_id'           =>  $user->id,
            'sub_cost_center_id'    =>  2
            ]);

        $I->seeCurrentUrlEquals($this->base_test->userCommons->usersIndexUrl);
        $I->see('Usuarios', 'h1');
        $I->see('Usuario actualizado correctamente.', '.alert-success');
        $I->see('Se ha actualizado el rol del usuario correctamente.', '.alert-success');
        $I->see('Actualización de centro de costos exitosa.', '.alert-success');
        
        /**
         * Final check
         */ 
        $I->see($this->base_test->userCommons->testUser['name'], 'tbody tr:first-child td:nth-child(2)');
        $I->see('Usuario Administrador', 'tbody tr:first-child td:nth-child(3)');
        $I->see($this->base_test->userCommons->testUser['email'], 'tbody tr:first-child td:nth-child(4)');
        $I->see('Desactivado', 'tbody tr:first-child td:nth-child(5)');

    }

    /**
     * Test the update user information functionality with invalid data
     * to check validation and errors
     *
     * @param @param \FunctionalTester $I
     * @return void
     */
    public function tryUpdateUserWithInvalidaData(FunctionalTester $I)
    {
        $I->am('admin user loged in');
        $I->wantTo('try update user info with invalid data');

        $I->seeAuthentication();

        $user = \sanoha\Models\User::create($this->base_test->userCommons->testUser);

        $I->amOnPage($this->base_test->userCommons->usersIndexUrl);
        $I->see('Usuarios', 'h1');

        $I->see($this->base_test->userCommons->testUser['name'], 'tr:first-child td:nth-child(2)');
        $I->see($this->base_test->userCommons->testUser['email'], 'tr:first-child td:nth-child(4)');
        $I->click($this->base_test->userCommons->testUser['name'], 'td a');

        $I->seeCurrentUrlEquals('/users/'. $user->id);
        $I->see('Detalles de Usuario', 'h1');
        $I->click('Editar', 'a');
        
        $I->seeCurrentUrlEquals('/users/'. $user->id .'/edit');
        $I->see('Actualizar Usuario', 'h1');

        $this->base_test->userCommons->testUser = [
            'password'  =>  '',
            'password_confirmation'  =>  ''
        ];

        $I->seeInFormFields('form', $this->base_test->userCommons->testUser);

        // new user data
        $this->base_test->userCommons->testUser = [
            'role_id'       =>    999,
            'name'          =>    'Andrew"#"',
            'lastname'      =>    '#$gasP',
            'email'         =>    'andrew.45698',
            'password'      =>    '987',
            'password_confirmation'      =>    '654'
        ];

        $I->submitForm('form', $this->base_test->userCommons->testUser, 'Actualizar');

        $I->seeCurrentUrlEquals('/users/'. $user->id .'/edit');

        $I->seeFormHasErrors();
        $I->seeFormErrorMessage('name', 'El nombre sólo puede contener letras y/o espacios.');
        $I->seeFormErrorMessage('lastname', 'El apellido sólo puede contener letras y/o espacios.');
        $I->seeFormErrorMessage('email', 'La dirección de correo electrónico no es válida.');
        $I->seeFormErrorMessage('role_id', 'Tipo de usuario inválido.');
        $I->seeFormErrorMessage('password', 'Las contraseñas no coinciden.');

        $I->submitForm('form', [
            'name'              => 'Eeeeeeeeeeeeeeeeeeddddddddddddddddddddddddddddddddddyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy',
            'lastname'          => 'Raaaaaaaaaaaaaaaaammmmmmmmmmmmmmmmmmmooooooooooooooooooooooonnnnnnnnnnnnnnnnnnnn',
            'email'             => 'travis.orbin@example.com', // the same as admin, for unique validation
            'role_id'           => 1,
            'password'          => 'edy',
            'password_confirmation'   => 'edy'
        ], 'Actualizar');

        $I->seeCurrentUrlEquals('/users/'. $user->id .'/edit');
        $I->seeFormHasErrors();
        $I->seeFormErrorMessage('name', 'El nombre debe tener máximo 50 caracteres.');
        $I->seeFormErrorMessage('lastname', 'El apellido debe tener máximo 50 caracteres.');
        $I->seeFormErrorMessage('email', 'La dirección de correo ya ha sido registrada.');
        $I->seeFormErrorMessage('password', 'La contraseña debe tener al menos 6 caracteres.');

        $I->submitForm('form', [
            'name'              => '',
            'lastname'          => 'Ramon',
            'email'             => '', // the same as admin, for unique validation
            'role_id'           => 1,
            'password'          => '',
            'password_confirmation'   => ''
        ], 'Actualizar');

        $I->seeCurrentUrlEquals('/users/'. $user->id .'/edit');
        $I->seeFormHasErrors();
        $I->seeFormErrorMessage('name', 'El nombre es un campo obligatorio.');
        $I->seeFormErrorMessage('email', 'La dirección de correo electrónico es oblogatoria.');
        $I->dontSee('Por favor digita una contraseña.', '.alert-danger');
    }
}