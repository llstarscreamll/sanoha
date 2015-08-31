<?php   namespace NoveltiesReports;

use \FunctionalTester;

use \common\ActivityReports     as ActivityReportsCommons;
use \common\SubCostCenters      as SubCostCentersCommons;
use \common\CostCenters         as CostCentersCommons;
use \common\Employees           as EmployeesCommons;
use \common\User                as UserCommons;
use \common\Permissions         as PermissionsCommons;
use \common\Roles               as RolesCommons;
use \common\Novelties           as NoveltiesCommons;

class SearchFlatReportCest
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
        
        // creo el usuairo administrador
        $this->noveltiesCommons = new NoveltiesCommons;
        $this->noveltiesCommons->createNoveltiesKinds();
        
        // le asigno los centros de costo al usuario administrador
        $this->user->subCostCenters()->sync([1,2,3,4]); // estos son los id's de los subcentros de los primeros dos proyectos o centros de costo
        
        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo las búsquedas en el reporte por defecto
     */
    public function search(FunctionalTester $I)
    {
        $I->am('un supervisor de proyecto');
        $I->wantTo('borrar una novedad reportada en la vista de sólo lectura');
        
        // datos de prueba
        $date = \Carbon\Carbon::now()->subDay();
        $data = [];
        
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'novelty_id'            =>  1,
            'comment'               =>  'prueba 1',
            'reported_at'           =>  '2015-08-08 08:00:00',
            'created_at'            =>  '2015-08-08 08:00:00',
            'updated_at'            =>  '2015-08-08 08:00:00',
            'deleted_at'            =>  null,
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>  2,
            'employee_id'           =>  2,
            'novelty_id'            =>  2,
            'comment'               =>  'prueba 2',
            'reported_at'           =>  '2015-08-07 16:00:00',
            'created_at'            =>  '2015-08-07 16:00:00',
            'updated_at'            =>  '2015-08-07 16:00:00',
            'deleted_at'            =>  null,
        ];
        
        \DB::table('novelty_reports')->insert($data);
        
        // estoy en el home
        $I->amOnPage('/home');
          
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // estoy en el index del módulo de reporte de novedades
        $I->seeCurrentUrlEquals('/noveltyReport');
        
        // veo los títulos que me dicen donde y en que centro de costo me encuentro
        $I->see('Reportes de Novedad', 'h1');
        $I->see('Proyecto Beteitiva', 'th');
        
        // veo en la tabla algunos registros creados
        $I->see('Trabajador 1', 'tbody tr:first-child td');
        $I->see('Trabajador 2', 'tbody tr:last-child td');
        $I->dontSee('Trabajador 3', 'tbody tr:last-child td');
        $I->dontSee('Trabajador 4', 'tbody tr:last-child td');
        
        $I->submitForm('form[name=search]', [
            'find'  =>  'dor 1'
        ]);
        
        $I->see('Trabajador 1', 'tbody tr:first-child td');
        $I->dontSee('Trabajador 2', 'tbody tr:last-child td');
        $I->dontSee('Trabajador 3', 'tbody tr:last-child td');
        
        $I->submitForm('form[name=search]', [
            'find'  =>  '',
            'from'  =>  '2015-08-07',
            'to'    =>  '2015-08-07'
        ]);
        
        $I->see('Trabajador 2', 'tbody tr:first-child td');
        $I->dontSee('Trabajador 1', 'tbody tr:last-child td');
        $I->dontSee('Trabajador 3', 'tbody tr:last-child td');
    }
}