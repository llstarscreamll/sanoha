<?php   namespace NoveltiesReports;

use \FunctionalTester;
use \common\BaseTest;

class FlatReportCest
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
     * Pruebo el reporte por defecto de reportes de novedades
     */
    public function defaultReport(FunctionalTester $I)
    {
        $I->am('un supervisor de proyecto');
        $I->wantTo('ver reporte plano de las novedades reportadas');
        
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
        $I->see('Licencia No Remunerada', 'tbody tr:first-child td');
        $I->see($date->toDateString(), 'tbody tr:first-child td');
    }
    
    /**
     * Pruebo el reporte por defecto de reportes de novedades vacío
     */
    public function defaultEmptyReport(FunctionalTester $I)
    {
        $I->am('un supervisor de proyecto');
        $I->wantTo('ver mensaje cuando no hay registros en reporte de novedades');
        
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
        $I->see('No se encontraron novedades...', 'tbody tr:first-child td .alert-warning');
    }
}