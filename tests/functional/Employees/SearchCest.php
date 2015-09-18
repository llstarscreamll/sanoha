<?php   namespace Employees;

use \FunctionalTester;
use \common\BaseTest as BaseTest;

class SearchCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTest;
        $base_test->employees();

        $I->amLoggedAs($base_test->admin_user);
        
        \sanoha\Models\Employee::create([
            'name'                  =>  'Alan',
            'lastname'              =>  'Silvestri',
            'identification_number' =>  '74265326',
            'email'                 =>  'alan.silvestri@example.com',
            'sub_cost_center_id'    =>  1,
            'position_id'           =>  1
        ]);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba la funcionalidad de búsqueda por nombre de empleado
     */ 
    public function searchByName(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('buscar un empleado por su nombre');
        
        $I->amOnPage('/employee');
        $I->see('Alan Silvestri', 'tbody tr td a');
        $I->see('Trabajador 1', 'tbody tr td a');
        $I->see('Trabajador 2', 'tbody tr td a');
        $I->see('Trabajador 3', 'tbody tr td a');
        $I->see('Trabajador 4', 'tbody tr td a');
        $I->see('Trabajador 5', 'tbody tr td a');
        $I->see('Trabajador 6', 'tbody tr td a');
        
        $I->seeElement('form', ['name' => 'search-form']);
        
        $I->submitForm('form[name=search-form]', ['find' => 'Alan'], 'Buscar');
        
        $I->seeCurrentUrlEquals('/employee?find=Alan');
        
        $I->see('Alan Silvestri', 'tbody tr td a');
        $I->dontSee('Trabajador 1', 'tbody tr td a');
        $I->dontSee('Trabajador 2', 'tbody tr td a');
        $I->dontSee('Trabajador 3', 'tbody tr td a');
        $I->dontSee('Trabajador 4', 'tbody tr td a');
        $I->dontSee('Trabajador 5', 'tbody tr td a');
        $I->dontSee('Trabajador 6', 'tbody tr td a');
    }
    
    /**
     * Prueba la funcionalidad de búsqueda por apellido
     */ 
    public function searchByLastname(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('buscar un empleado por su nombre');
        
        $I->amOnPage('/employee');
        $I->see('Alan Silvestri', 'tbody tr td a');
        $I->see('Trabajador 1', 'tbody tr td a');
        $I->see('Trabajador 2', 'tbody tr td a');
        $I->see('Trabajador 3', 'tbody tr td a');
        $I->see('Trabajador 4', 'tbody tr td a');
        $I->see('Trabajador 5', 'tbody tr td a');
        $I->see('Trabajador 6', 'tbody tr td a');
        
        $I->seeElement('form', ['name' => 'search-form']);
        
        $I->submitForm('form[name=search-form]', ['find' => 'Silves'], 'Buscar');
        
        $I->seeCurrentUrlEquals('/employee?find=Silves');
        
        $I->see('Alan Silvestri', 'tbody tr td a');
        $I->dontSee('Trabajador 1', 'tbody tr td a');
        $I->dontSee('Trabajador 2', 'tbody tr td a');
        $I->dontSee('Trabajador 3', 'tbody tr td a');
        $I->dontSee('Trabajador 4', 'tbody tr td a');
        $I->dontSee('Trabajador 5', 'tbody tr td a');
        $I->dontSee('Trabajador 6', 'tbody tr td a');
    }
    
    /**
     * Prueba la funcionalidad de búsqueda por número de cédula
     */ 
    public function searchByCC(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('buscar un empleado por su nombre');
        
        $I->amOnPage('/employee');
        $I->see('Alan Silvestri', 'tbody tr td a');
        $I->see('Trabajador 1', 'tbody tr td a');
        $I->see('Trabajador 2', 'tbody tr td a');
        $I->see('Trabajador 3', 'tbody tr td a');
        $I->see('Trabajador 4', 'tbody tr td a');
        $I->see('Trabajador 5', 'tbody tr td a');
        $I->see('Trabajador 6', 'tbody tr td a');
        
        $I->seeElement('form', ['name' => 'search-form']);
        
        $I->submitForm('form[name=search-form]', ['find' => '74265'], 'Buscar');
        
        $I->seeCurrentUrlEquals('/employee?find=74265');
        
        $I->see('Alan Silvestri', 'tbody tr td a');
        $I->dontSee('Trabajador 1', 'tbody tr td a');
        $I->dontSee('Trabajador 2', 'tbody tr td a');
        $I->dontSee('Trabajador 3', 'tbody tr td a');
        $I->dontSee('Trabajador 4', 'tbody tr td a');
        $I->dontSee('Trabajador 5', 'tbody tr td a');
        $I->dontSee('Trabajador 6', 'tbody tr td a');
    }
}