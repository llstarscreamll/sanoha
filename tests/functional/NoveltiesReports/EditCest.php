<?php
namespace NoveltiesReports;

use \FunctionalTester;
use \common\BaseTest;

class EditCest
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
     * 
     */ 
    public function updateReportedNovelty(FunctionalTester $I)
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
        
        $I->am('un ingeniero del área tecnica');
        $I->wantTo('actualizar una novedad reportada');
        
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
        $I->click('Editar', 'a');
        
        // estoy en la página de edición
        $I->seeCurrentUrlEquals('/noveltyReport/1/edit');
        $I->see('Actualizar Detalles de Novedad', 'legend');
        $I->see('Detalles de Novedad', 'legend');
        
        // veo que la información mostrada corresponde con lo que hay en BD
        $I->seeElement('form', ['method' => 'POST']);
        $I->seeOptionIsSelected('employee_id', 'B1 Trabajador 1');
        $I->seeOptionIsSelected('novelty_id', 'Licencia No Remunerada');
        $I->seeElement('input', ['value' => $date->toDateString()]);
        $I->see('prueba', 'textarea');

        // no debo ver a este trabajador el cual no tiene el cargo que requiere
        // el módulo, para este caso sólo requiere mineros y supervisores de proyectos
        $I->dontSee('Williams John', 'select optgroup option');
        
        $I->submitForm('form', [
            'employee_id'   =>  2,
            'novelty_id'    =>  2,
            'reported_at'   =>  $date = \Carbon\Carbon::now()->toDateString(),
            'comment'       =>  'Comentario de prueba'
        ],
        'Actualizar');
        
        // estoy en la página del detalle de la novedad reportada
        $I->seeCurrentUrlEquals('/noveltyReport/1');
        $I->see('Detalles de Novedad', 'legend');
        
        $I->see('Novedad actualizada exitosamente.', '.alert-success');
        
        // veo que la información mostrada corresponde con la que actualizé
        $I->seeElement('input', ['value' => 'Proyecto Beteitiva - Bocamina 2', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => 'B2 Trabajador 2', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => 'Permiso No Remunerado', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => $date, 'disabled' => 'disabled']);
        $I->see('Comentario de prueba', 'textarea:disabled');
    }
}