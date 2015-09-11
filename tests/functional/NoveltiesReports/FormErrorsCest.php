<?php   namespace NoveltiesReports;

use \FunctionalTester;
use \common\BaseTest;

class FormErrorsCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTest;
        $base_test->noveltyReports();

        $I->amLoggedAs($base_test->admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }
    
    /**
     * Pruebo los mensajes de error en la búsqueda de novedades
     */
    public function searchErrors(FunctionalTester $I)
    {
        $I->am('un ingheniero del área tecnica');
        $I->wantTo('ver mensages al buscar una novedad');
        
        // estoy en el home
        $I->amOnPage('/home');
          
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        $I->seeCurrentUrlEquals('/noveltyReport');
        
        /* -- Campos vacíos, nada pasa, pueden estar vacíos --  */
        $I->submitForm('#search', [
            'from'  =>  '',
            'to'    =>  '',
            'find'  =>  ''
        ]);
        
        $I->dontSeeElement('div', ['class' => 'alert alert-danger alert-dismissible']);
        
        /* -- Formatos inválidos --  */
        $I->submitForm('form[name=search]', [
            'from'  =>  'sdf456',
            'to'    =>  'asdf345345',
            'find'  =>  '$#%&%'
        ]);
        
        $I->see('La fecha de inicio del filtro tiene un formato inválido.', '.text-danger');
        $I->see('La fecha de fin del filtro tiene un formato inválido.', '.text-danger');
        $I->see('Sólo puedes digitar letras, números y/o espacios.', '.text-danger');
    }

    /**
     * Pruebo los mensages de error en al reportar una novedad
     */ 
    public function createErrors(FunctionalTester $I)
    {
        $I->am('un ingheniero del área tecnica');
        $I->wantTo('ver mensages al reportar una novedad');
        
        // estoy en el home
        $I->amOnPage('/home');
          
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // voy a la página de reporte de novedad con un empleado seleccionado
        $I->amOnPage('/noveltyReport/create');
        
        /* -- Campos vacíos --  */
        $I->submitForm('form', [
            'employee_id'    => '',
            'novelty_id'        => '',
            'reported_at'    => '',
            'comment'        => ''
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('Selecciona un trabajador de la lista.', '.text-danger');
        $I->see('Debes seleccionar la novedad que vas a reportar.', '.text-danger');
        $I->see('Selecciona la fecha en que se presentó la novedad.', '.text-danger');
        
         /* -- Formatos inválidos --  */
        $I->submitForm('form', [
            'employee_id'    => 'dfg',
            'novelty_id'        => 'dfg',
            'reported_at'    => 'dfg',
            'comment'        => '/()$%'
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('Identificador de empleado inválido.', '.text-danger');
        $I->see('Identificador de tipo de novedad inválido.', '.text-danger');
        $I->see('La fecha tiene un formato inválido.', '.text-danger');
        $I->see('El comentario sólo debe contener letras y/o espacios.', '.text-danger');
        
        /* -- Campos que no existen en la base de datos --  */
        $date = \Carbon\Carbon::now();
        $I->submitForm('form', [
            'employee_id'    => 657,
            'novelty_id'     => 900,
            'reported_at'    => $date->toDateString(),
            'comment'        => ''
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('El trabajador no existe, trabajador inválido.', '.text-danger');
        $I->see('No existe el tipo novedad, novedad inválida.', '.text-danger');
        
        /* -- Fecha anterior al rango permitido -- */
        $I->submitForm('form', [
            'employee_id'    => 1,
            'novelty_id'     => 2,
            'reported_at'    => $date->startOfYear()->toDateString(),
            'comment'        => ''
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('La fecha debe ser depúes del '.\Carbon\Carbon::now()->subDays(30)->toDateString().'.', '.text-danger');
        
        /* -- Fecha posterior al rango permitido -- */
        $I->submitForm('form', [
            'employee_id'    => 1,
            'novelty_id'     => 2,
            'reported_at'    => $date->endOfYear()->toDateString(),
            'comment'        => ''
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('La fecha debe ser de antes del '.\Carbon\Carbon::now()->addDays(5)->toDateString().'.', '.text-danger');
    }
}