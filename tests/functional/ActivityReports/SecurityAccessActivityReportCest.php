<?php namespace ActivityReports;

use \FunctionalTester;
use \Carbon\Carbon;

use \sanoha\Models\User         as UserModel;

use \common\ActivityReports     as ActivityReportsCommons;
use \common\SubCostCenters      as SubCostCentersCommons;
use \common\CostCenters         as CostCentersCommons;
use \common\Employees           as EmployeesCommons;
use \common\MiningActivities    as MiningActivitiesCommons;
use \common\User                as UserCommons;
use \common\Permissions         as PermissionsCommons;
use \common\Roles               as RolesCommons;

class SecurityAccessActivityReportCest
{
    public function _before(FunctionalTester $I)
    {
        //creo centros de costo
        $this->costCentersCommons = new CostCentersCommons;
        $this->costCentersCommons->createCostCenters();
        
        // creo subcentros de costo
        $this->subCostCentersCommons = new SubCostCentersCommons;
        $this->subCostCentersCommons->createSubCostCenters();
        
        // creo los empleados
        $this->employeeCommons = new EmployeesCommons;
        $this->employeeCommons->createMiningEmployees();
        
        // creo actividades mineras
        $this->miningActivities = new MiningActivitiesCommons;
        $this->miningActivities->createMiningActivities();

        // creo los permisos para el módulo de reporte de actividades mineras
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createActivityReportsModulePermissions();
        
        // creo los roles de usuario y añado todos los permisos al rol de administrador
        $this->rolesCommons = new RolesCommons;
        $this->rolesCommons->createBasicRoles();
        
        // creo el usuairo administrador
        $this->userCommons = new UserCommons;
        $this->user = $this->userCommons->createAdminUser();
        $this->userCommons->createUsers();
        
        // creo algunos reportes de actividades mineras
        //$this->activityReportsCommons = new ActivityReportsCommons;
        //$this->activityReportsCommons->createActivityReports(2);
        
        // le asigno los centros de costo al usuario administrador
        $this->user->subCostCenters()->sync([1,2,3,4]); // estos son los id's de los subcentros de los proyectos o centros de costo

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
        //dd(\sanoha\Models\CostCenter::where('id', 1)->get()->lists('name'));
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
        UserModel::find($this->user->id)->subCostCenters()->sync([1]);
        
        // veo que el cambio está hecho en la base de datos
        $I->seeRecord('sub_cost_center_owner', [
            'user_id'               =>  $this->user->id,
            'sub_cost_center_id'    =>  1
            ]);
        
        // no veo algún otro centro de costo o proyecto asociado
        $I->dontSeeRecord('sub_cost_center_owner', [
            'user_id'           =>  $this->user->id,
            'sub_cost_center_id'    =>  2
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
        UserModel::find($this->user->id)->subCostCenters()->sync([]);
        
        // compruebo en la base de datos Beteitiva
        $I->dontSeeRecord('sub_cost_center_owner', [
            'user_id'           =>  $this->user->id,
            'cost_center_id'    =>  1
            ]);
        
        // compruebo en la base de datos Sanoha
        $I->dontSeeRecord('sub_cost_center_owner', [
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
        UserModel::find($this->user->id)->subCostCenters()->sync([]);
        
        // compruebo en la base de datos Beteitiva
        $I->dontSeeRecord('sub_cost_center_owner', [
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