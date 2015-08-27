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

class FlatReportCest
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

        $I->amLoggedAs($this->userCommons->adminUser);
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
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  10,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  '2015-08-07 08:00:00',
            'created_at'            =>  '2015-08-08 08:00:00',
            'updated_at'            =>  '2015-08-08 08:00:00',
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);
        
        $I->am('supervisor del Projecto Beteitiva');
        $I->wantTo('ver reporte de actividades reportadas individualmente en orden cronológico');
    
        $I->amOnPage('/home');
        $I->see('Proyecto Beteitiva', 'a');
        $I->click('Proyecto Beteitiva', 'a'); // el proyecto o centro de costo creado en UserCommons
        
        // hago clic en el vinculo al proyecto que tengo acceso
        $I->seeCurrentUrlEquals('/activityReport');
        $I->seeInSession('current_cost_center_id', 1); // el id del centro de costos ue seleccioné
        $I->seeInSession('current_cost_center_name', 'Proyecto Beteitiva'); // el id del centro de costos ue seleccioné

        // titulo de la página
        $I->see('Reporte de Actividades');
        $I->seeElement('a', ['class' => 'btn btn-default', 'title' => 'Reporte de Registros Individuales']);
        $I->click('Reporte de Registros Individuales', 'a');
        
        // no veo el mensaje "No se encontraron registros..."
        $I->see('Reporte de Labores Mineras', 'h1');
        $I->dontSee('No se encontraron registros...', '.alert-danger');
        
        // veo la tabla donde se muestran los datos
        $I->seeElement('table');
        
        // veo que tipo de reporte y de que proyecto es el reporte
        $I->see('Proyecto Beteitiva', 'th');
        $I->see('1', 'tbody tr td a');
        $I->see('Trabajador 1', 'tbody tr td');
        $I->see('Avance Roca', 'tbody tr td');
        $I->see('2', 'tbody tr td');
        $I->see('5.000', 'tbody tr td');
        $I->see('10.000', 'tbody tr td');
        $I->see('10', 'tbody tr td'); // las horas trabajadas
    }
    
    /**
     * Pruebo la función de buscar en el reporte individual re labores mineras reportadas
     */
    public function searchOnFlatReport(FunctionalTester $I)
    {
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  '2015-08-07 08:00:00',
            'created_at'            =>  '2015-08-07 08:00:00',
            'updated_at'            =>  '2015-08-07 08:00:00',
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
            'reported_at'           =>  '2015-08-05 08:00:00',
            'created_at'            =>  '2015-08-05 08:00:00',
            'updated_at'            =>  '2015-08-05 08:00:00',
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
            'reported_at'           =>  '2015-08-06 08:00:00',
            'created_at'            =>  '2015-08-06 08:00:00',
            'updated_at'            =>  '2015-08-06 08:00:00',
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);
        
        $I->am('supervisor del Projecto Beteitiva');
        $I->wantTo('probar función de búsqueda en el reporte de actividades individuales');
    
        $I->amOnPage('/home');
        $I->see('Proyecto Beteitiva', 'a');
        $I->click('Proyecto Beteitiva', 'a'); // el proyecto o centro de costo creado en UserCommons
        
        // hago clic en el vinculo al proyecto que tengo acceso
        $I->seeCurrentUrlEquals('/activityReport');
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
        $I->submitForm('form[name=search]', [
            'find'  =>  'Trabajador 1',
            'from'  =>  '2015-08-06',
            'to'    =>  '2015-08-09'
        ], 'Buscar');
        
        // el resultado de la búsqueda me debe mostrar sólo lo de "Trabajador 1"
        $I->see('Trabajador 1', 'tbody tr td');
        $I->dontSee('Trabajador 2', 'tbody tr td');
        $I->dontSee('Trabajador 3', 'tbody tr td');
    }
}