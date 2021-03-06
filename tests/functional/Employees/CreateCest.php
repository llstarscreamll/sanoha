<?php
namespace Employees;

use \FunctionalTester;
use \common\BaseTest as BaseTest;

class CreateCest
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
     * Pruebo la funcionalidad para crear empleados
     */
    public function createEmployee(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('crear nuevo empleado');
        
        $I->amOnPage('/employee');
        $I->click('Crear Empleado', 'a');
        
        $I->seeCurrentUrlEquals('/employee/create');
        $I->see('Crear Empleado', 'h2');
        
        $I->submitForm('form', [
            'name'                          =>  'Alan',
            'lastname'                      =>  'Silvestri',
            'identification_number'         =>  '74265326',
            'email'                         =>  'alan.silvestri@example.com',
            'phone'                         =>  '123456789',
            'sub_cost_center_id'            =>  1,
            'position_id'                   =>  1,
            'authorized_to_drive_vehicles'  =>  true
        ], 'Crear');
        
        $I->seeCurrentUrlEquals('/employee');
        $I->see('Empleado creado correctamente.', '.alert-success');
        
        $I->see('Silvestri Alan', 'tbody tr:first-child td');
        $I->see('Proyecto Beteitiva Bocamina 1', 'tbody tr:first-child td');
        $I->see('Minero', 'tbody tr:first-child td');
        $I->see('alan.silvestri@example.com', 'tbody tr:first-child td');
        
        $I->seeRecord('employees', [
            'name'                          =>  'Alan',
            'lastname'                      =>  'Silvestri',
            'identification_number'         =>  '74265326',
            'email'                         =>  'alan.silvestri@example.com',
            'phone'                         =>  '123456789',
            'sub_cost_center_id'            =>  1,
            'position_id'                   =>  1,
            'authorized_to_drive_vehicles'  =>  true
        ]);
    }
    
    /**
     * Pruebo la funcionalidad para crear empleado, sin digitar el email
     */
    public function createWithoutEamil(FunctionalTester $I)
    {
        $date = \Carbon\Carbon::now()->subMonth();
        
        \DB::table('employees')->insert([
            'name'                  =>  'Chris',
            'lastname'              =>  'Coleman',
            'identification_number' =>  '123456',
            'email'                 =>  '',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1,
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null
        ]);
        
        $I->am('admin de recurso humano');
        $I->wantTo('crear nuevo empleado sin digitar email');
        
        $I->amOnPage('/employee');
        $I->click('Crear Empleado', 'a');
        
        $I->seeCurrentUrlEquals('/employee/create');
        $I->see('Crear Empleado', 'h2');
        
        $I->submitForm('form', [
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '7895',
            'email'                 =>  '',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
        ], 'Crear');
        
        $I->seeCurrentUrlEquals('/employee');
        $I->see('Empleado creado correctamente.', '.alert-success');
        
        $I->see('Silvestri Alan', 'tbody tr:first-child td');
        $I->see('Proyecto Beteitiva Bocamina 1', 'tbody tr:first-child td');
        $I->see('Minero', 'tbody tr:first-child td');
        $I->see('', 'tbody tr:first-child td');
    }
}
