<?php   namespace ActivityReports;

use \FunctionalTester;
use \Users\_common\UserCommons;
use \ActivityReports\_common\ActivityReportsCommons;

class SelectCostCenterCest
{
    public function _before(FunctionalTester $I)
    {
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();
        $this->userCommons->haveUsers(10); // creo 10 usuarios
        $this->userCommons->haveEmployees(10); // crea 10 empleados + 2 por defecto
        $this->userCommons->haveMiningActivities();
        
        $this->activityReportsCommons = new ActivityReportsCommons;
        // creo reportes ficticios para los días requeridos
        $this->activityReportsCommons->haveActivityReports(date('Y-m-d'), 2);

        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de forzar a elejir el centro de costos si es que
     * no se ha seleccionado uno, pues se genera un error si se quiere acceder
     * a algún reporte de actividades sin haber elegido un centro de costos
     * primero...
     * 
     * @param $I
     */ 
    public function accessActivityReportWithoutSelectCostCenter(FunctionalTester $I)
    {
        $I->am('un usuario administrador del sistema');
        $I->wantTo('ver que ya no hay error accediendo a algún reporte de usuario sin elejir el centro de costos');
        
        // estoy en la página principal
        $I->amOnPage('/home');
        
        // accedo al reporte sin elegir el centro de costos
        $I->amOnPage('/activityReport');
        
        // y soy redireccionado a una página en la que debo elejir el centro
        // de costos, pues no he elejido uno
        $I->seeCurrentUrlEquals('/activityReport/selectCostCenter');
        $I->see('Proyecto Asignados');
        
        // elijo el centro de costos que quiero
        $I->click('Proyecto Beteitiva', 'a');
        
        // y soy redireccionado a la página de reporte de actividades mineras
        $I->seeCurrentUrlEquals('/activityReport');
        
        // veo que se muestra un título con el reporte del centro de costos que elejí
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
        $I->amLoggedAs($this->userCommons->adminUser);
        
        // borro los datos del anterior proyecto seleccionado
        \Session::forget('currentCostCenterActivities');
        
        // intento acceder de nuevo al área de reportes
        $I->amOnPage('/activityReport');
        
        // veo que soy redirigido a la página para elejir el centro de costos
        $I->seeCurrentUrlEquals('/activityReport/selectCostCenter');
        $I->see('Proyecto Asignados');
        
        // elijo el centro de costos que quiero
        $I->click('Proyecto Sanoha', 'a');
        
        // y soy redireccionado a la página re reporte de actividades
        $I->seeCurrentUrlEquals('/activityReport');
        
        // y veo que la cabecera del reporte tiene el nombre del proyecto o
        // centro de costos que elejí
        $I->see('Proyecto Sanoha');
        
    }
}