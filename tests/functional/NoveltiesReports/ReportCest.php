<?php   namespace NoveltiesReports;

use \FunctionalTester;
use \common\BaseTest;

class ReportCest
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
     * Pruebo la funcionalidad de reportar una novedad de un trabajador
     */
    public function reportEmployeeNovelty(FunctionalTester $I)
    {
        $I->am('un supervisor de proyecto');
        $I->wantTo('reportar una novedad de un empelado');
        
        // estoy en el home
        $I->amOnPage('/home');
        
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // voy a la página de reporte de novedad con un empleado seleccionado
        $I->amOnPage('/noveltyReport/create?employee_id=1');
        
        // veo los títulos que me dicen donde y en que centro de costo me encuentro
        $I->see('Reportar Novedad', 'legend');
        
        // veo los campos necesarios para reportar la novedad
        $I->seeElement('form');
        $I->seeElement('input', ['name' => 'attended', 'type' => 'checkbox']); // campo informativo nada mas
        $I->seeElement('select', ['name' => 'employee_id']);
        $I->seeElement('select', ['name' => 'novelty_id']);
        $I->seeElement('input', ['name' => 'reported_at', 'readonly' => 'readonly']);
        $I->seeElement('textarea', ['name' => 'comment']);
        $I->seeElement('button', ['type' => 'submit']);
            
        // veo que el usuario que venía en la url está ya seleccionado
        $I->seeOptionIsSelected('#employee_id', 'B1 Trabajador 1');
        
        // veo una lista con los empleados del proyecto
        $I->see('B1 Trabajador 1', 'option');
        $I->see('B2 Trabajador 2', 'option');
        
        // pero  no veo los trabajadores de otros proyectos
        $I->dontSee('B1 Trabajador 3', 'option');
        $I->dontSee('Trabajador 4 B2', 'option');
        
        // veo en un select los tipos de novedades
        $I->see('Licencia No Remunerada', 'option');
        $I->see('Permiso No Remunerado', 'option');
        $I->see('Incapacidad Enfermedad Generar', 'option');
        $I->see('Incapacidad Accidente Trabajo', 'option');
        $I->see('Licencia Luto', 'option');
        $I->see('Licencia Peternidad', 'option');

        // lleno el formulario con los datos necesarios y lo envío
        $I->submitForm('form', [
           'employee_id'    => '1',
           'novelty_id'     => '1',
           'reported_at'    => \Carbon\Carbon::now()->subDays(1)->toDateString(),
           'comment'        => 'el trabajador no vino a trabajar'
        ], 'Reportar');

        // veo que estoy en el index del módulo de reporte de novedades
        $I->seeCurrentUrlEquals('/noveltyReport/create');

        // veo mensaje de éxito en la operación
        $I->see('Novedad reportada exitosamente.', '.alert-success');
        
    }
}