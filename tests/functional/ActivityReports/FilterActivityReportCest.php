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

class FilterActivityReportCest
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
        
        // creo algunos reportes de actividades mineras
        $this->activityReportsCommons = new ActivityReportsCommons;
        $this->activityReportsCommons->createYesterdayActivities();

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

        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Probar el reporte generado por defecto
     * 
     * @param
     */ 
    public function testDefaultReport(FunctionalTester $I)
    {
        // deferencia en días desde hoy para crear el reporte y los datos de prueba
        $days = 1; // uno porque el reporte que se genera por defecto es del día anterior
        $project_id = 2; // centro de costos o Proyecto, se creó en SystemCommons
        
        // inicio el test
        $I->am('supervisor del Projecto Beteitiva');
        $I->wantTo('ver que actividades se han reportado el día de ayer');
    
        $I->amOnPage('/home');
        $I->see('Proyecto Beteitiva', 'a');
        $I->click('Proyecto Beteitiva', 'a'); // el proyecto o centro de costo creado en UserCommons
        
        // hago clic en el vinculo al proyecto que tengo acceso
        $I->seeCurrentUrlEquals('/activityReport');
        $I->seeInSession('current_cost_center_id', 1); // el id del centro de costos ue seleccioné
        
        // titulo de la página
        $I->see('Reporte de Actividades');
        
        // no veo el mensaje "No se encontraron registros..."
        $I->dontSee('No se encontraron registros...', '.alert-danger');
        
        // veo la tabla donde se muestran los datos
        $I->see('', 'table');
        
        // veo que tipo de reporte y de que proyecto es el reporte
        $I->see('Proyecto Beteitiva', 'th h3');
        
        $report_date = \Carbon\Carbon::now()->subDays(1)->format('d-m-Y');
        
        // el rango de fechas del reporte debe ser mostrado en la tabla
        $I->see('Desde '.$report_date, 'th h4');
        $I->see('Hasta '.$report_date, 'th h4');
        
        //veo que el nombre corto de todas las actividades mineras están en
        // la cabecera de la tabla, pero tienen su nombre completo en el atributo title
        $miningActivities = \sanoha\Models\MiningActivity::orderBy('name')->get();

        foreach ($miningActivities as $activity) {
            $I->see($activity->short_name, 'th');
            $I->seeElement('th span', ['title' => $activity->name, 'data-toggle' => 'tooltip']);
        }
        
        // veo las actividades registradas, los datos de prueba están creados en _common/ActivityReports.php
        $I->see('Trabajador 1 B1', 'tbody tr:nth-child(1) td');
        $I->see('5', 'tbody tr:nth-child(1) td');
        $I->see('125.000', 'tbody tr:nth-child(1) td'); // 5 * 25000 = 125000
        
        $I->see('Trabajador 2 B2', 'tbody tr:nth-child(2) td');
        $I->see('2', 'tbody tr:nth-child(2) td');
        $I->see('20.000', 'tbody tr:nth-child(2) td'); // 2 * 10000 = 20000
        
        $I->see('Trabajador 2 B2', 'tbody tr:nth-child(2) td'); // en la misma fila porque es el mismo trabajador
        $I->see('4', 'tbody tr:nth-child(2) td');
        $I->see('48.000', 'tbody tr:nth-child(2) td'); // 4 * 12000 = 48000
    }
}