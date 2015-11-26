<?php
namespace Employees;

use \FunctionalTester;
use \common\BaseTest as BaseTest;

class EditCest
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
     * Pruebo la funciónalidad de editar un empleado
     */
    public function edit(FunctionalTester $I)
    {
        // el empleado a editar, tendrá id 9 porque ya hay 8 creados
        \sanoha\Models\Employee::create([
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'email'                 =>  'alan.silvestri@example.com',
            'phone'                 =>  '159753',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
            ]);
        
        // otro cargo para actualizar, ya está creado el cargo "Minero"
        \sanoha\Models\Position::create([
            'name'  =>  'Operario'
            ]);
        
        $I->am('admin de recurso humano');
        $I->wantTo('ediat la info de un empleado');
        
        $I->amOnPage('/employee');
        $I->see('Silvestri Alan', 'tbody tr td a');
        $I->click('Silvestri Alan', 'tbody tr td a');
        
        // el 9 es porque ya hay 8 empleados creados
        $I->seeCurrentUrlEquals('/employee/13');
        $I->see('Detalles de Empleado', 'h2');
        $I->click('Editar', 'a');
        $I->seeCurrentUrlEquals('/employee/13/edit');
        $I->see('Actualizar Empleado', 'h2');
        
        $I->seeInFormFields('form', [
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'email'                 =>  'alan.silvestri@example.com',
            'phone'                 =>  '159753',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
        ]);
        
        $I->submitForm('form', [
            'name'                  =>  'Alan Robert',
            'lastname'              =>  'Silvestri Smith',
            'identification_number' =>  '99987',
            'email'                 =>  'alan.silvestri@example.com',
            'phone'                 =>  '5555555',
            'sub_cost_center_id'    =>  3,
            'position_id'           =>  2
        ], 'Actualizar');
        
        $I->seeCurrentUrlEquals('/employee/13');
        $I->see('Empleados', 'h1');
        $I->see('Empleado actualizado correctamente.', '.alert-success');

        // veo el campo actualizado en la base de datos
        $I->seeRecord('employees', [
            'lastname'              =>  'Silvestri Smith',
            'identification_number' =>  '99987',
            'email'                 =>  'alan.silvestri@example.com',
            'phone'                 =>  '5555555',
        ]);
    }
}
