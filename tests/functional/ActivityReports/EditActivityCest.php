<?php   namespace ActivityReports;

use \FunctionalTester;
use \common\BaseTest;

class EditActivityCest
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
     * Prueba la funcionalidad de editar una actividad minera reportada
     */ 
    public function editReportedActivity(FunctionalTester $I)
    {
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('editar una actividad minera de un trabajador de mi proyecto');
        
        $date = \Carbon\Carbon::now()->subMonth();
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  4,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);
        
        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        $I->amOnPage('/activityReport/1/edit');
        $I->see('Actualizar Detalles de Labor Minera', 'legend');
        
        // veo los campos del formulario con los datos del registro
        $I->seeOptionIsSelected('employee_id', 'Trabajador 1 B1');
        $I->seeOptionIsSelected('mining_activity_id', 'Vagoneta de Carbón | VC');
        $I->seeElement('input', ['value' => '2']);
        $I->seeElement('input', ['value' => '5000']);
        $I->seeElement('input', ['value' => '4']);
        $I->seeElement('input', ['value' => $date->toDateString()]);
        $I->see('test', 'textarea');
        
        // la tabla donde se muestra lo que ha reportado en el día
        $I->seeElement('table');
        //dd(\sanoha\Models\ActivityReport::all()->toArray());
        $I->dontSee('No hay actividades registradas...', '.alert-warning');
        
        $I->see('2', 'tbody tr td');
        $I->see('10.000', 'tbody tr td');
        
        $I->submitForm('form', [
            'employee_id'           =>  2,
            'mining_activity_id'    =>  2,
            'quantity'              =>  4,
            'price'                 =>  '10000',
            'worked_hours'          =>  4,
            'reported_at'           =>  $date->toDateString(),
            'comment'               =>  'test'
        ]);
        
        $I->seeCurrentUrlEquals('/activityReport/1');
        $I->see('Detalles de Labor', 'legend');
        $I->see('Actualización de Actividad Minera exitosa.', '.alert-success');
        
        $I->amOnPage('/activityReport/1/edit');
        $I->see('Actualizar Detalles de Labor Minera', 'legend');
        
        // veo los campos del formulario con los datos del registro
        $I->seeOptionIsSelected('employee_id', 'Trabajador 2 B2');
        $I->seeOptionIsSelected('mining_activity_id', 'Vagoneta de Roca | VR');
        $I->seeElement('input', ['value' => '4']);
        $I->seeElement('input', ['value' => '10000']);
        $I->seeElement('input', ['value' => $date->toDateString()]);
        $I->see('test', 'textarea');
        
        // la tabla donde se muestra lo que ha reportado en el día
        $I->seeElement('table');
        $I->dontSee('No hay actividades registradas... ', '.alert');
        $I->see('4', 'tbody tr td');
        $I->see('40.000', 'tbody tr td');
    }
}