<?php
namespace ActivityReports;

use \FunctionalTester;
use \common\BaseTest;

class SelectCostCenterCest
{
    public function _before(FunctionalTester $I)
    {
        $this->base_test = new BaseTest;
        $this->base_test->activityReports();

        $I->amLoggedAs($this->base_test->admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de forzar a elejir el centro de costos si es que
     * no se ha seleccionado uno, pues se genera un error si se quiere acceder
     * a algún reporte de actividades sin haber elegido un centro de costos
     * anteriormente...
     * 
     * @param FunctionalTester $I
     */
    public function forceAccessActivityReport(FunctionalTester $I)
    {
        $this->base_test->activityReportsCommons->createYesterdayActivities();
        
        $I->am('un usuario administrador del sistema');
        $I->wantTo('acceder a los reportes de actividades sin barra de navegacion');
        
        // estoy en la página principal
        $I->amOnPage('/home');
        
        // accedo al reporte sin elegir el centro de costos
        $I->amOnPage('/activityReport');
        
        // y soy redireccionado a una página en la que debo elejir el centro
        // de costos, pues no he elejido uno
        $I->seeCurrentUrlEquals('/activityReport/selectCostCenter');
        $I->see('Proyectos Asignados');
        
        // elijo el centro de costos que quiero
        $I->click('Proyecto Beteitiva', '.list-group-item a');
        
        // y soy redireccionado a la página de reporte de actividades mineras
        $I->seeCurrentUrlEquals('/activityReport/individual');
        
        // veo que se muestra un título con el reporte del centro de costos que elejí
        $I->dontSee('No se encontraron registros...');
        $I->see('Proyecto Beteitiva', 'h3');
        
        // cierro mi sessión
        $I->click('Salir', 'a');
        $I->dontSeeAuthentication();

        // intento acceder al reporte
        $I->amOnPage('/activityReport');
        
        // veo que soy redirijido a la página de login
        $I->seeCurrentUrlEquals('/auth/login');
        $I->see('Iniciar Sesión', '.panel-heading');
        
        // inicio sesisón de nuevo
        $I->amLoggedAs($this->base_test->admin_user);
        
        // borro los datos del anterior proyecto seleccionado
        \Session::forget('current_cost_center_id');
        
        // intento acceder de nuevo al área de reportes
        $I->amOnPage('/activityReport');
        
        // veo que soy redirigido a la página para elejir el centro de costos
        $I->seeCurrentUrlEquals('/activityReport/selectCostCenter');
        $I->see('Proyecto Asignados');
        
        // elijo el centro de costos que quiero
        $I->click('Proyecto Sanoha', 'a');
        
        // y soy redireccionado a la página re reporte de actividades
        $I->seeCurrentUrlEquals('/activityReport/individual');
        
        // y veo que la cabecera del reporte tiene el nombre del proyecto o
        // centro de costos que elejí
        $I->see('Proyecto Sanoha');
    }
}
