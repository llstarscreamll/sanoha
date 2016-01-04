<?php
namespace ActivityReports;

use \FunctionalTester;
use \common\BaseTest;

class FlatReportCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTest;
        $base_test->activityReports();

        $I->amLoggedAs($base_test->admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo que los datos que se muestran en el reporte de actividades mineras
     * registradas sean correctos
     */
    public function flatReportView(FunctionalTester $I)
    {
        // creo dos actividades para hacer test de los cálculos de totales por empleado
        $date = \Carbon\Carbon::now()->subDay();
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  10,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  2,
            'quantity'              =>  4,
            'price'                 =>  '10000',
            'worked_hours'          =>  8,
            'comment'               =>  'test two',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->subDay()->toDateTimeString(),
            'created_at'            =>  $date->subDay()->toDateTimeString(),
            'updated_at'            =>  $date->subDay()->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);
        
        // debo ver la actividad aún cuando el empleado haya sido elmiminado
        \sanoha\Models\Employee::destroy(1);
        
        $I->am('supervisor del Proyecto Beteitiva');
        $I->wantTo('ver reporte de actividades reportadas individuales');
    
        $I->amOnPage('/home');
        $I->see('Proyecto Beteitiva', 'a');
        $I->click('Proyecto Beteitiva', 'a'); // el proyecto o centro de costo creado en UserCommons

        // hago clic en el vinculo al proyecto que tengo acceso
        $I->seeCurrentUrlEquals('/activityReport/individual');
        $I->seeInSession('current_cost_center_id', 1); // el id del centro de costos ue seleccioné
        $I->seeInSession('current_cost_center_name', 'Proyecto Beteitiva'); // el id del centro de costos ue seleccioné

        // titulo de la página
        $I->see('Reporte de Actividades');
        $I->seeElement('a', ['class' => 'btn btn-default', 'title' => 'Reporte de Registros Individuales']);
        $I->click('Reporte de Registros Individuales', 'a');
        
        $I->see('Reporte de Labores Mineras', 'h1');
        
        // veo la tabla donde se muestran los datos
        $I->seeElement('table');
        
        // no veo el mensaje "No se encontraron registros..."
        $I->dontSee('No se encontraron registros...', '.alert-danger');
        
        // veo que tipo de reporte y de que proyecto es el reporte
        $I->see('Proyecto Beteitiva', 'th');
        // veo el rango de fechas del reporte
        $report_date = \Carbon\Carbon::now();
        $I->dontSee('Desde', 'th');
        $I->dontSee('Hasta', 'th');
        
        // --- actividad 1
        $I->see('1', 'tbody tr:first-child td a');
        $I->see('Trabajador 1', 'tbody tr:first-child td');
        $I->see('Vagoneta de Carbón', 'tbody tr:first-child td');
        $I->see('2', 'tbody tr:first-child td'); // cantidad de la actividad 1
        $I->see('5.000', 'tbody tr:first-child td'); // precio individual 1
        $I->see('10.000', 'tbody tr:first-child td'); // precio total de la actividad 1
        // --- actividad 2
        $I->see('2', 'tbody tr:nth-child(2) td a');
        $I->see('Vagoneta de Roca', 'tbody tr:nth-child(2) td');
        $I->see('4', 'tbody tr:nth-child(2) td'); // cantidad de la actividad 2
        $I->see('10.000', 'tbody tr:nth-child(2) td'); // precio individual 2
        $I->see('40.000', 'tbody tr:nth-child(2) td'); // precio total de la actividad 2
    }
    
    /**
     * Pruebo la función de buscar en el reporte individual de labores mineras reportadas
     */
    public function searchOnFlatReport(FunctionalTester $I)
    {
        $date = \Carbon\Carbon::now();
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->copy()->subDay()->toDateTimeString(),
            'created_at'            =>  $date->copy()->subDay()->toDateTimeString(),
            'updated_at'            =>  $date->copy()->subDay()->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  2,
            'quantity'              =>  4,
            'price'                 =>  '7000',
            'comment'               =>  '',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->copy()->subMonths(2)->toDateTimeString(),
            'created_at'            =>  $date->copy()->subMonths(2)->toDateTimeString(),
            'updated_at'            =>  $date->copy()->subMonths(2)->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  3,
            'mining_activity_id'    =>  3,
            'quantity'              =>  3,
            'price'                 =>  '1000',
            'comment'               =>  '',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->copy()->subMonths(2)->subDay()->toDateTimeString(),
            'created_at'            =>  $date->copy()->subMonths(2)->subDay()->toDateTimeString(),
            'updated_at'            =>  $date->copy()->subMonths(2)->subDay()->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);
        
        $I->am('supervisor del Projecto Beteitiva');
        $I->wantTo('hacer busqueda en el reporte de actividades individuales');
    
        $I->amOnPage('/home');
        $I->see('Proyecto Beteitiva', 'a');
        $I->click('Proyecto Beteitiva', 'a'); // el proyecto o centro de costo creado en UserCommons

        // hago clic en el vinculo al proyecto que tengo acceso
        $I->seeCurrentUrlEquals('/activityReport/individual');
        $I->seeElement('a', ['class' => 'btn btn-default', 'title' => 'Reporte de Registros Individuales']);
        $I->click('Reporte de Registros Individuales', 'a');
        
        // no veo el mensaje "No se encontraron registros..."
        $I->see('Reporte de Labores Mineras', 'h1');
        $I->dontSee('No se encontraron registros...', '.alert-danger');
        
        // veo todos los registros que hay creados
        $I->see('Trabajador 1', 'tbody tr td');
        $I->see('Trabajador 2', 'tbody tr td');
        $I->see('Trabajador 3', 'tbody tr td');
        
        // veo le formulario de búsqueda
        $I->seeElement('form', ['name' => 'search']);
        
        // busco por fechas y nombre
        $I->submitForm('form[name=search]', [
            'find'  =>  'Trabajador 1',
            'from'  =>  $date->startOfMonth()->toDateString(),
            'to'    =>  $date->endOfMonth()->toDateString()
        ], 'Buscar');
        
        // el resultado de la búsqueda me debe mostrar sólo lo de "Trabajador 1"
        $I->see('Trabajador 1', 'tbody tr td');
        $I->dontSee('Trabajador 2', 'tbody tr td');
        $I->dontSee('Trabajador 3', 'tbody tr td');
        
        // busco sólo por fechas
        $I->submitForm('form[name=search]', [
            'find'  =>  '',
            'from'  =>  $date->startOfMonth()->toDateString(),
            'to'    =>  $date->endOfMonth()->toDateString()
        ], 'Buscar');
        
        // el resultado de la búsqueda me debe mostrar sólo lo de "Trabajador 1"
        $I->see('Trabajador 1', 'tbody tr td');
        $I->dontSee('Trabajador 2', 'tbody tr td');
        $I->dontSee('Trabajador 3', 'tbody tr td');
    }
}
