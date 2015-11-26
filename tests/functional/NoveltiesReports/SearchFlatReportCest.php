<?php
namespace NoveltiesReports;

use \FunctionalTester;
use \common\BaseTest;

class SearchFlatReportCest
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
     * Pruebo las búsquedas en el reporte por defecto
     */
    public function search(FunctionalTester $I)
    {
        $I->am('un supervisor de proyecto');
        $I->wantTo('buscar una novedad en el sistema');
        
        // datos de prueba
        $date = \Carbon\Carbon::now()->subDay();
        $data = [];
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'novelty_id'            =>  1,
            'comment'               =>  'prueba 1',
            'reported_at'           =>  '2015-08-08 08:00:00',
            'created_at'            =>  '2015-08-08 08:00:00',
            'updated_at'            =>  '2015-08-08 08:00:00',
            'deleted_at'            =>  null,
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>  2,
            'employee_id'           =>  2,
            'novelty_id'            =>  2,
            'comment'               =>  'prueba 2',
            'reported_at'           =>  '2015-08-07 16:00:00',
            'created_at'            =>  '2015-08-07 16:00:00',
            'updated_at'            =>  '2015-08-07 16:00:00',
            'deleted_at'            =>  null,
        ];
        
        \DB::table('novelty_reports')->insert($data);
        
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
        $I->see('Trabajador 2', 'tbody tr:last-child td');
        $I->dontSee('Trabajador 3', 'tbody tr:last-child td');
        $I->dontSee('Trabajador 4', 'tbody tr:last-child td');
        
        $I->submitForm('form[name=search]', [
            'find'  =>  'dor 1'
        ]);
        
        $I->see('Trabajador 1', 'tbody tr:first-child td');
        $I->dontSee('Trabajador 2', 'tbody tr:last-child td');
        $I->dontSee('Trabajador 3', 'tbody tr:last-child td');
        
        $I->submitForm('form[name=search]', [
            'find'  =>  '',
            'from'  =>  '2015-08-07',
            'to'    =>  '2015-08-07'
        ]);
        
        $I->see('Trabajador 2', 'tbody tr:first-child td');
        $I->dontSee('Trabajador 1', 'tbody tr:last-child td');
        $I->dontSee('Trabajador 3', 'tbody tr:last-child td');
    }
}
