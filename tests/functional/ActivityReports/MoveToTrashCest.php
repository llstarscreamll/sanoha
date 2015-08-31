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

class MoveToTrashCest
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
        
        // le asigno los centros de costo al usuario administrador
        $this->user->subCostCenters()->sync([1,2,3,4]); // estos son los id's de los subcentros de los primeros dos proyectos o centros de costo

        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }
    
    /**
     * Pruebo la funcionalidad de borrar varios registros a la vez a traves de
     * tabla donde se muestran todos
     */
    public function moveToTrashSeveral(FunctionalTester $I)
    {
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('mover varias actividades reportadas a la ves');
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  \Carbon\Carbon::now()->subDays(1)->toDateTimeString(),
            'created_at'            =>  \Carbon\Carbon::now()->subDays(1)->toDateTimeString(),
            'updated_at'            =>  \Carbon\Carbon::now()->subDays(1)->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  2,
            'quantity'              => 4,
            'price'                 =>  '10000',
            'comment'               =>  'test test',
            'reported_by'           =>  1,
            'reported_at'           =>  \Carbon\Carbon::now()->subDays(2)->toDateTimeString(),
            'created_at'            =>  \Carbon\Carbon::now()->subDays(2)->toDateTimeString(),
            'updated_at'            =>  \Carbon\Carbon::now()->subDays(2)->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);
        
        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        $I->see('Reporte de Labores Mineras', 'h1');
        $I->see('Proyecto Beteitiva', 'th h3');
        
        // veo los registros en la tabla
        $I->see('Trabajador 1', 'tbody tr td');
        $I->see('Trabajador 2', 'tbody tr td');
        
        // veo el formulario
        $I->seeElement('form[name=table-form]');
        
        // envío el formulario con los registros que quiero borrar
        $I->submitForm('form[name=table-form]', [
            'id'  =>  [true, true]
        ]);
        
        $I->see('Las actividades han sido movidas a la papelera correctamente.', '.alert-success');
    }

    /**
     * Pruebo la funconalidad de borrar una actividad reportada en la base de datos
     * mediante el botón "Mover a la papelera" de la vista de sólo lectura de la
     * actividad
     */ 
    public function moveToTrash(FunctionalTester $I)
    {
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('mover a papelera una actividad minera de un trabajador de mi proyecto');
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  \Carbon\Carbon::now()->subDays(1)->toDateTimeString(),
            'created_at'            =>  \Carbon\Carbon::now()->subDays(1)->toDateTimeString(),
            'updated_at'            =>  \Carbon\Carbon::now()->subDays(1)->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);
        
        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        $I->see('Reporte de Labores Mineras', 'h1');
        $I->see('Proyecto Beteitiva', 'th h3');
        
        $report_date = \Carbon\Carbon::now()->subDays(1)->format('d-m-Y');
        
        // el rango de fechas del reporte debe ser mostrado en la tabla
        $I->see('Trabajador 1', 'tbody tr td');
        
        // estoy en la página de vista en sólo lectura
        $I->click('1', 'tbody tr td a');
        $I->seeCurrentUrlEquals('/activityReport/1');
        $I->see('Detalles de Labor', 'legend');
        
        // doy clic al botón "Mover a la Papelera"
        $I->click('Confirmar', 'button');
        
        // veo mensage de exito en la operación
        $I->see('La actividad se ha movido a la papelera correctamente.');
        $I->see('Reporte de Labores Mineras', 'h1');
        $I->see('Proyecto Beteitiva', 'th h3');
        $I->see('No se encontraron registros...', '.alert-danger');
        $I->dontSeeRecord('activity_reports', $data[0]);
    }
}