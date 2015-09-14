<?php   namespace Employees;

use \FunctionalTester;
use \common\BaseTest as BaseTest;

class TrashCest
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
     * Pruebo la funcionalidad de borrar varios empleados a la vez
     */
    public function trashMany(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('eliminar varios empleados a la vez');
        
        $I->amOnPage('/employee');
        $I->see('Trabajador 1', 'tbody tr td a');
        $I->see('Trabajador 2', 'tbody tr td a');
        
        $I->submitForm('form[name=table-form]', [
            'id'  =>  [true, true]
        ]);

        $I->seeCurrentUrlEquals('/employee');
        $I->see('Los empleados han sido movidos a la papelera correctamente.', '.alert-success');
    }

    /**
     * Pruebo la funcionalidad de borrar un empleado en la vista "Detalles de Empleado"
     */ 
    public function trashOne(FunctionalTester $I)
    {
        \sanoha\Models\Employee::create([
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'email'                 =>  'alan.silvestri@example.com',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
            ]);
        
        $I->am('admin de recurso humano');
        $I->wantTo('eliminar un empleado');
        
        $I->amOnPage('/employee');
        $I->see('Alan Silvestri', 'tbody tr td a');
        $I->click('Alan Silvestri', 'tbody tr td a');
        
        // el 9 es porque ya hay 8 empleados creados
        $I->seeCurrentUrlEquals('/employee/13');
        $I->see('Detalles de Empleado', 'h2');
        
        // los campos con los detalles del empleado
        $I->seeElement('input', ['value' => 'Alan']);
        $I->seeElement('input', ['value' => 'Silvestri']);
        $I->seeElement('input', ['value' => '74265326']);

        $I->click('Confirmar', 'button');
        
        $I->seeCurrentUrlEquals('/employee');
        
        $I->see('El empleado ha sido movido a la papelera correctamente.', '.alert-success');
    }
}