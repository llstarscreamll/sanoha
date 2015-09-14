<?php   namespace Employees;

use \FunctionalTester;
use \common\BaseTest as BaseTest;

class GetAllEmployeesCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTest;
        $base_test->employees();

        $I->amLoggedAs($base_test->admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la vista que me devuelve todos los empleados creados
     */ 
    public function getAllEmployeesView(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('ver la lista de los empleados del sistema');
        
        $I->amOnPage('/employee');
        
        $I->see('Empleados', 'h1');
        $I->seeElement('table');
        
        // veo en la tabla los empleados registrados
        $I->see('Trabajador 1 B1', 'tbody tr:last-child td a');
        $I->see('Trabajador1@example.com', 'tbody tr:last-child td');
        $I->see('Minero', 'tbody tr:last-child td');
        $I->see('Proyecto Beteitiva Bocamina 1', 'tbody tr:last-child td');
        $I->see('Trabajador 2 B2', 'tbody tr td a');
        $I->see('Trabajador 3 B1', 'tbody tr td a');
        $I->see('Trabajador 4 B2', 'tbody tr td a');
    }
}