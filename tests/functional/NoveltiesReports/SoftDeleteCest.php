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

class SoftDeleteCest
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
     * Pruebo el borrado de una novedad reportadan en la vista de sólo
     * lectura de la novedad
     */ 
    public function softDeleteNovelty(FunctionalTester $I)
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
            'comment'               =>  'prueba',
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
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
        $I->click('1', 'tbody tr:first-child td a');
        
        // estoy en la página del detalle de la novedad reportada
        $I->seeCurrentUrlEquals('/noveltyReport/1');
        $I->see('Detalles de Novedad', 'legend');
        $I->see('Proyecto Beteitiva', 'h1 small');

        // veo que la información mostrada corresponde con lo que hay en BD
        $I->seeElement('input', ['value' => 'Proyecto Beteitiva - Bocamina 1', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => 'Trabajador 1 B1', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => 'Licencia No Remunerada', 'disabled' => 'disabled']);
        $I->seeElement('input', ['value' => $date->toDateString(), 'disabled' => 'disabled']);
        $I->see('prueba', 'textarea:disabled');
        
        // doy clic al botón que borra la novedad
        $I->click('Confirmar', 'form .btn-danger');
        
        // veo que estoy en el index del módulo
        $I->seeCurrentUrlEquals('/noveltyReport');
        
        // veo el mensaje de éxito en la operación
        $I->see('La novedad ha sido movida a la papelera correctamente.', '.alert-success');
        
        // ya no veo el registro en el reporte
        $I->dontSee('Trabajador 1', 'tbody tr:first-child td');
    }
    
    /**
     * Mover varias novedades a la papelera a la vez en el reporte plano de novedades
     */
    public function softDeleteManyNovelties(FunctionalTester $I)
    {
        $I->am('un supervisor de proyecto');
        $I->wantTo('borrar varias novedades reportadas a la vez desde el index del  módulo');
        
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
        
        $data[] = [
            'sub_cost_center_id'    =>  2,
            'employee_id'           =>  2,
            'novelty_id'            =>  2,
            'comment'               =>  'prueba 2',
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
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
        
        // selecciono los registros que quiero borrar
        $I->click('#novelty-report-1');
        $I->click('#novelty-report-2');
        
        //envío el formulario
        $I->submitForm('form[name=table-form]', [
            'id'  =>    [true, true]
        ]);
        
        $I->see('Las novedades han sido movidos a la papelera correctamente.', '.alert-success');
    }
}