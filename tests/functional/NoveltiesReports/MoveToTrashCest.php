<?php   namespace NoveltiesReports;

use \FunctionalTester;
use \common\BaseTest;

class MoveToTrashCest
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
     * Pruebo mover muchos registros a la papelera a la vez
     */
    public function moveToTrashMany(FunctionalTester $I)
    {
        // datos de prueba
        $date = \Carbon\Carbon::now()->subDay();
        $data = [];
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'novelty_id'            =>  1,
            'comment'               =>  'prueba',
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null,
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'novelty_id'            =>  2,
            'comment'               =>  'test',
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null,
        ];
        
        \DB::table('novelty_reports')->insert($data);
        
        $I->am('un supervisor de proyecto');
        $I->wantTo('borrar una novedad reportada');
        
        // estoy en el home
        $I->amOnPage('/home');
          
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // estoy en el index del módulo de reporte de novedades
        $I->seeCurrentUrlEquals('/noveltyReport');
        
        // veo los títulos que me dicen donde y en que centro de costo me encuentro
        $I->see('Reportes de Novedad', 'h1');
        
        $I->seeElement('table');
        $I->see('Trabajador 1', 'tbody tr:first-child td');
        $I->see('Trabajador 2', 'tbody tr:last-child td');
        
        $I->submitForm('form[name=table-form]', [
            'id'  =>  [true, true]
        ]);
        
        $I->see('Las novedades han sido movidos a la papelera correctamente.', '.alert-success');
    }

    /**
     * Pruebo mover a la papelera un reporte de novedad desde la vista de sólo lectura
     */ 
    public function moveToTrashOne(FunctionalTester $I)
    {
        // datos de prueba
        $date = \Carbon\Carbon::now()->subDay();
        $data = [];
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'novelty_id'            =>  1,
            'comment'               =>  'prueba',
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null,
        ];
        
        \DB::table('novelty_reports')->insert($data);
        
        $I->am('un supervisor de proyecto');
        $I->wantTo('borrar una novedad reportada');
        
        // estoy en el home
        $I->amOnPage('/home');
          
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // estoy en el index del módulo de reporte de novedades
        $I->seeCurrentUrlEquals('/noveltyReport');
        
        // veo los títulos que me dicen donde y en que centro de costo me encuentro
        $I->see('Reportes de Novedad', 'h1');
        $I->see('Proyecto Beteitiva', 'th');
        
        // veo en la tabla algunos registros creados
        $I->see('Trabajador 1', 'tbody tr:first-child td');
        $I->click('1', 'tbody tr:first-child td a');
        
        // estoy en la página del detalle de la novedad reportada
        $I->seeCurrentUrlEquals('/noveltyReport/1');
        $I->see('Detalles de Novedad', 'legend');
        $I->see('Proyecto Beteitiva', 'h1 small');
        
        // veo que la información mostrada corresponde con lo que hay en BD
        $I->seeElement('input', ['value' => 'Proyecto Beteitiva - Bocamina 1', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => 'Trabajador 1 B1', 'disabled' => 'disabled']);
        
        $I->click('Confirmar', 'button');
        
        $I->see('La novedad ha sido movida a la papelera correctamente.', '.alert-success');
    }
}