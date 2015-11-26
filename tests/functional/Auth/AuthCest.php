<?php
namespace Auth;

use \FunctionalTester;

class AuthCest
{
    private $adminUser;

    public function __construct()
    {
        $this->adminUser = [
            'name'          =>  'Travis',
            'lastname'      =>  'Orbin',
            'email'         =>  'admin@example.com',
            'password'      =>  '123456',
            'remember_token'=>  null,
            'created_at'    => date('Y-m-d'),
            'updated_at'    => date('Y-m-d')
        ];
    }

    public function _before(FunctionalTester $I)
    {
        $this->adminUser['password'] = \Hash::make($this->adminUser['password']);
        $I->haveRecord('users', $this->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Login with valid credentials
     */
    public function loginUsingValidCredentials(FunctionalTester $I)
    {
        $user = $this->adminUser;
        $user['password'] = '123456';

        $I->dontSeeAuthentication();
        $I->am('an admin user');
        $I->wantTo('login on system');

        $I->amOnPage('auth/login');
        $I->see('Iniciar Sesión');
        $I->submitForm('form', ['email' => $user['email'], 'password' => $user['password']], 'Login');
        $I->seeAuthentication();
        $I->amOnPage('/home');
        $I->see('Salir', 'a');
    }

    /**
     * Try to login with invalid credentials, to test validators
     * and error messages...
     */
    public function loginUsingInvalidCredentials(FunctionalTester $I)
    {
        $user = $this->adminUser;
        $user['password'] = '123456';

        $I->dontSeeAuthentication();
        $I->am('an registered user');
        $I->wantTo('login with wrong data');

        // fill form with invalid data
        $I->amOnPage('auth/login');
        $I->see('Iniciar Sesión');
        $I->submitForm('form', ['email' => 'wrong.user@example.com', 'password' => 'wrong.password'], 'Login');
        $I->dontSeeAuthentication();
        $I->seeCurrentUrlEquals('/auth/login');
        $I->see('Correo o contraseña incorrectos, intenta de nuevo.', '.alert-danger');

        
        //Fill form with no data
        $I->submitForm('form', ['email' => '', 'password' => ''], 'Login');
        $I->dontSeeAuthentication();
        $I->seeCurrentUrlEquals('/auth/login');
        $I->seeElement('.alert-danger');
        $I->see('Debes digitar tu dirección de correo electrónico.', '.alert-danger');
        $I->see('Digita tu contraseña.', '.alert-danger');
    }
}
