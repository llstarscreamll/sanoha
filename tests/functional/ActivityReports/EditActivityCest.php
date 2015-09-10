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

class EditActivityCest
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
     * 
     */ 
    public function editReportedActivity(FunctionalTester $I)
    {
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('editar una actividad minera de un trabajador de mi proyecto');
        
        $date = \Carbon\Carbon::now();
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  4,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  '2015-08-07 08:00:00',
            'created_at'            =>  '2015-08-08 08:00:00',
            'updated_at'            =>  '2015-08-08 08:00:00',
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);
        
        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        
        $I->amOnPage('/activityReport/1/edit');
        $I->see('Actualizar Detalles de Labor Minera', 'legend');
        
        // veo los campos del formulario con los datos del registro
        $I->seeOptionIsSelected('employee_id', 'Trabajador 1 B1');
        $I->seeOptionIsSelected('mining_activity_id', 'Vagoneta de Carbón | VC');
        $I->seeElement('input', ['value' => '2']);
        $I->seeElement('input', ['value' => '5000']);
        $I->seeElement('input', ['value' => '4']);
        $I->seeElement('input', ['value' => '2015-08-07']);
        $I->see('test', 'textarea');
        
        $I->submitForm('form', [
            'employee_id'           =>  2,
            'mining_activity_id'    =>  2,
            'quantity'              =>  4,
            'price'                 =>  '10000',
            'worked_hours'          =>  4,
            'reported_at'           =>  $date->toDateString(),
            'comment'               =>  'test'
        ]);
        
        $I->seeCurrentUrlEquals('/activityReport/1');
        $I->see('Detalles de Labor', 'legend');
        $I->see('Actualización de Actividad Minera exitosa.', '.alert-success');
        
        $I->amOnPage('/activityReport/1/edit');
        $I->see('Actualizar Detalles de Labor Minera', 'legend');
        
        // veo los campos del formulario con los datos del registro
        $I->seeOptionIsSelected('employee_id', 'Trabajador 2 B2');
        $I->seeOptionIsSelected('mining_activity_id', 'Vagoneta de Roca | VR');
        $I->seeElement('input', ['value' => '4']);
        $I->seeElement('input', ['value' => '10000']);
        $I->seeElement('input', ['value' => $date->toDateString()]);
        $I->see('test', 'textarea');
    }
}