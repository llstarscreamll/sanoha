<?php   namespace ActivityReports;

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

class SelectCostCenterCest
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
        
        // le asigno los centros de costo al usuario administrador
        $this->user->subCostCenters()->sync([1,2,3,4]); // estos son los id's de los subcentros de los primeros dos proyectos o centros de costo
        
        // creo algunos reportes de actividades mineras
        $this->activityReportsCommons = new ActivityReportsCommons;
        $this->activityReportsCommons->createYesterdayActivities();

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
    public function forceAccessActivityReport(FunctionalTester $I)
    {
        $I->am('un usuario administrador del sistema');
        $I->wantTo('acceder a los reportes de actividades sin barra de navegación');
        
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
        $I->seeCurrentUrlEquals('/activityReport');
        
        //dd(\sanoha\Models\ActivityReport::all()->toArray());
        //\sanoha\Models\ActivityReport::all()->toArray();
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
        $I->amLoggedAs($this->userCommons->adminUser);
        
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
        $I->seeCurrentUrlEquals('/activityReport');
        
        // y veo que la cabecera del reporte tiene el nombre del proyecto o
        // centro de costos que elejí
        $I->see('Proyecto Sanoha');
        
    }
}