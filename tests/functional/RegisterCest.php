<?php
use \FunctionalTester;

use \Users\_common\UserCommons as UserCommons;

class RegisterCest
{

    private $userCommons;
    private $valid_form;
    private $invalid_form;

    public function _before(FunctionalTester $I)
    {

        $this->userCommons = new UserCommons;
        $this->userCommons->haveUserRoles();

        $this->valid_form = [
            'name'      =>      'Travis',
            'lastname'  =>      'Orbin',
            'email'     =>      'travis.orbin@gmail.com',
            'password'  =>      '123456',
            'password_confirmation'  =>      '123456'
            ];

        $this->invalid_form = [
            'name'      =>      '!"#$fhdfg',
            'lastname'  =>      ';:]SYdas #$wf#dfw',
            'email'     =>      'travis.orbin',
            'password'  =>      'fgh',
            'password_confirmation'  =>      'f55'
            ];
    }

    public function _after(FunctionalTester $I)
    {
    }


    /**
     * Try to register a user with invalid data
     * to test validator and error messages.
     */
    public function registerWithWrongData(FunctionalTester $I)
    {
        $I->am(' a user unregistered');
        $I->wantTo('try register me with wrong data');

        $I->amOnPage('/auth/login');
        $I->click('Regístrate');
        $I->seeCurrentUrlEquals('/auth/register');
        $I->submitForm('form', $this->invalid_form, 'Register');

        $I->seeCurrentUrlEquals('/auth/register');
        $I->seeElement('.alert-danger');
        $I->see('El nombre sólo puede contener letras y/o espacios.', 'li');
        $I->see('El apellido sólo puede contener letras y/o espacios.', 'li');
        $I->see('La contraseña debe tener al menos 6 caracteres.', 'li');
        $I->see('Las contraseñas no coinciden.', 'li');
    }

    /**
     * Register a user with valida data
     */
    public function registerUser(FunctionalTester $I)
    {
        $I->am('a new user');
        $I->wantTo('register me on system');

        $I->amOnPage('/auth/login');
        $I->click('Regístrate');
        $I->seeCurrentUrlEquals('/auth/register');
        $I->submitForm('form', $this->valid_form, 'Guardar');
        $I->amOnPage('/home');
        $I->seeRecord('users', ['email' => $this->valid_form['email']]);
        $I->seeAuthentication();

    }
}