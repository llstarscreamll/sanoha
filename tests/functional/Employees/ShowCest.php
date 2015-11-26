<?php
namespace Employees;

use \FunctionalTester;
use \common\BaseTest as BaseTest;

class ShowCest
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
     * Pruebo la funciónalidad de ver detalles de un empleado
     */
    public function show(FunctionalTester $I)
    {
        \sanoha\Models\Employee::create([
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'email'                 =>  'alan.silvestri@example.com',
            'sub_cost_center_id'    =>  1,
            'phone'                 =>  '123456',
            'position_id'           =>  1
            ]);
        
        $I->am('admin de recurso humano');
        $I->wantTo('ver la info de un empleado');
        
        $I->amOnPage('/employee');
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->click('Silvestri Alan', 'tbody tr td a');
        
        // el 9 es porque ya hay 8 empleados creados
        $I->seeCurrentUrlEquals('/employee/13');
        $I->see('Detalles de Empleado', 'h2');
        
        // los campos con los detalles del empleado
        $I->seeElement('input', ['value' => 'Alan']);
        $I->seeElement('input', ['value' => 'Silvestri']);
        $I->seeElement('input', ['value' => '74265326']);
        $I->seeElement('input', ['value' => '123456']); // teléfono
        $I->seeElement('input', ['value' => 'alan.silvestri@example.com']);
        $I->seeElement('input', ['value' => 'Proyecto Beteitiva Bocamina 1']);
        $I->seeElement('input', ['value' => 'Minero']);
        $I->seeElement('input', ['value' => 'No']); // autorizado para manejo de vehículos
    }
}
