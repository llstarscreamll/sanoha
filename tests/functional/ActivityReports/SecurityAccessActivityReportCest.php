<?php namespace ActivityReports;

use \FunctionalTester;
use Users\_common\UserCommons;
use \sanoha\Models\User;

class SecurityAccessActivityReportCest
{
    /**
     * 
     */
    private $user;
    
    public function _before(FunctionalTester $I)
    {
        $this->userCommons = new UserCommons;
        $this->user = $this->userCommons->createAdminUser();
        $this->userCommons->haveEmployees(10);
        $this->userCommons->haveMiningActivities();
        
        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba que se tienen sólo los links de acceso a los reportes de actividades
     * de los proyectos asignados.
     */ 
    public function costCentersActivityReports(FunctionalTester $I)
    {
        $I->am('supervisor minero');
        $I->wantTo('revisar si tengo links de acceso al módulo de reporte de actividades de los Proyectos Beteitiva y Sanoha');
        
        // estoy en el home donde debo ver los links de acceso
        $I->amOnPage('/home');
        
        // veo los links de acceso a mis proyectos
        $I->see('Reporte de Actividades');
        $I->see('Proyecto Beteitiva', 'a');
        $I->see('Proyecto Sanoha', 'a');
        
        // pero no veo los links de acceso a los demas proyectos
        $I->dontSee('Proyecto Cazadero', 'a');
        $I->dontSee('Proyecto Pinos', 'a');
    }
    
    /**
     * Pruebo que tengo solo acceso al centro de costo asignado.
     */
    public function acceesToBeteitivaActivityReports(FunctionalTester $I)
    {
        $I->am('supervisor minero');
        $I->wantTo('quiero ver si tengo acceso a los reportes de actividades mineras de algún proyecto');
        
        // asigno un centro de costos al usuario
        User::find($this->user->id)->costCenter()->sync([1]);
        
        // veo que el cambio está hecho en la base de datos
        $I->seeRecord('cost_center_owner', [
            'user_id'           =>  $this->user->id,
            'cost_center_id'    =>  1
            ]);
        
        // no veo algún otro centro de costo o proyecto asociado
        $I->dontSeeRecord('cost_center_owner', [
            'user_id'           =>  $this->user->id,
            'cost_center_id'    =>  2
            ]);
        
        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // sólo veo el centro de costos Beteitiva
        $I->see('Proyecto Beteitiva', 'a');
        
        // y lógicamente no veo el proyecto Sanoha, pues no lo tengo asignado
        $I->dontSee('Proyecto Sanoha', 'a');
    }
    
    /**
     * Pruebo que no tengo acceso a ningún reporte de labores mineras de ningún proyecto
     * y el mensaje de notificación de tal caso en la barra de navegación.
     */
    public function noAccessToCostCentersActivityReports(FunctionalTester $I)
    {
        $I->am('supervisor minero');
        $I->wantTo('revisar que no tengo acceso a los reportes de actividad minera de los proyectos');
        
        // borro cualquier proyecto que tenga asociado el usuario
        User::find($this->user->id)->costCenter()->sync([]);
        
        // compruebo en la base de datos Beteitiva
        $I->dontSeeRecord('cost_center_owner', [
            'user_id'           =>  $this->user->id,
            'cost_center_id'    =>  1
            ]);
        
        // compruebo en la base de datos Sanoha
        $I->dontSeeRecord('cost_center_owner', [
            'user_id'           =>  $this->user->id,
            'cost_center_id'    =>  2
            ]);
        
        // estoy en el home
        $I->amOnPage('/home');
        
        // no veo ningún link de acceso a algún proyecto
        $I->dontSee('Proyecto Beteitiva', 'a');
        $I->dontSee('Proyecto Sanoha', 'a');
        
        // veo un mensaje que me indica que no tengo ningún proyecto asignado
        $I->see('No tienes proyectos asignados', 'a');
    }
    
    /**
     * Pruebo un acceso brusco a algún proyecto que no tnego asignado.
     */ 
    public function forceAccessToCostCenter(FunctionalTester $I)
    {
        $I->am('supervisor minero');
        $I->wantTo('probar si puedo tener acceso a algún proyecto manipulando las variables de la url');
        
         // borro cualquier proyecto que tenga asociado el usuario
        User::find($this->user->id)->costCenter()->sync([]);
        
        // compruebo en la base de datos Beteitiva
        $I->dontSeeRecord('cost_center_owner', [
            'user_id'           =>  $this->user->id,
            'cost_center_id'    =>  1
            ]);
        
         // estoy en el home
        $I->amOnPage('/home');
        
        // no veo ningún link de acceso a algún proyecto
        $I->dontSee('Proyecto Beteitiva', 'a');
        $I->dontSee('Proyecto Sanoha', 'a');
        
        // veo el mensaje del sistema me muestra que efectivamente ningún proyecto tengo asociado
        $I->see('No tienes proyectos asignados', 'a');
        
        // intento acceder bruscamente al proyecto Sanoha manipulando las variables de la url
        $I->amOnPage('/activityReport/project/1');
        
        // no veo la cabecera normal del módulo de reporte de actividades
        $I->dontSee('Reporte de Actividades', 'h3');
        
        // pero veo un mensaje de alerta que me dice que no tengo acceso
        $I->see('No tienes los permisos necesarios para acceder a estos datos.', '.alert-warning');
        
    }
}