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

class EditCest
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

    // tests
    public function updateReportedNovelty(FunctionalTester $I)
    {
        // datos de prueba
        $date = \Carbon\Carbon::now()->subDay();
        $data = [];
        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'novelty_id'            =>  1,
            'comment'               =>  'prueba',
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null,
        ];
        
        \DB::table('novelty_reports')->insert($data);
        
        $I->am('un ingheniero del área tecnica');
        $I->wantTo('actualizar una novedad reportada');
        
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
        $I->click('1', 'tbody tr:first-child td a');
        
        // estoy en la página del detalle de la novedad reportada
        $I->seeCurrentUrlEquals('/noveltyReport/1');
        $I->see('Detalles de Novedad', 'legend');
        $I->click('Editar', 'a');
        
        // estoy en la página de edición
        $I->seeCurrentUrlEquals('/noveltyReport/1/edit');
        $I->see('Actualizar Detalles de Novedad', 'legend');
        $I->see('Detalles de Novedad', 'legend');
        
        // veo que la información mostrada corresponde con lo que hay en BD
        $I->seeElement('form', ['method' => 'POST']);
        $I->seeOptionIsSelected('employee_id', 'Trabajador 1 B1');
        $I->seeOptionIsSelected('novelty_id', 'Licencia No Remunerada');
        $I->seeElement('input', ['value' => $date->toDateString()]);
        $I->see('prueba', 'textarea');
        
        $I->submitForm('form', [
            'employee_id'   =>  2,
            'novelty_id'    =>  2,
            'reported_at'   =>  $date = \Carbon\Carbon::now()->toDateString(),
            'comment'       =>  'Comentario de prueba'
        ],
        'Actualizar');
        
        // estoy en la página del detalle de la novedad reportada
        $I->seeCurrentUrlEquals('/noveltyReport/1');
        $I->see('Detalles de Novedad', 'legend');
        
        $I->see('Novedad actualizada exitosamente.', '.alert-success');
        
        // veo que la información mostrada corresponde con la que actualizé
        $I->seeElement('input', ['value' => 'Proyecto Beteitiva - Bocamina 2', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => 'Trabajador 2 B2', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => 'Permiso No Remunerado', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => $date, 'disabled' => 'disabled']);
        $I->see('Comentario de prueba', 'textarea:disabled');
        
    }
}