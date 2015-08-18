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

class CreateCest
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
     * 
     */
    public function reportEmployeeNovelty(FunctionalTester $I)
    {
        $I->am('un supervisor de proyecto');
        $I->wantTo('reportar una novedad de un empelado');
        
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // voy a la página de reporte de novedad con un empleado seleccionado
        $I->amOnPage('/noveltyReport/create?employee_id=1');
        
        // veo los títulos que me dicen donde y en que centro de costo me encuentro
        $I->see('Reportar Novedad Proyecto Beteitiva', 'h1');
        
        // veo los campos necesarios para reportar la novedad
        $I->seeElement('form');
        $I->seeElement('input', ['name' => 'attended', 'type' => 'checkbox']); // campo informativo nada mas
        $I->seeElement('select', ['name' => 'employee_id']);
        $I->seeElement('select', ['name' => 'novelty_id']);
        $I->seeElement('input', ['name' => 'reported_at', 'readonly' => 'readonly']);
        $I->seeElement('textarea', ['name' => 'comment']);
        $I->seeElement('button', ['type' => 'submit']);
            
        //dd(\sanoha\Models\SubCostCenter::where('cost_center_id', 1)->with('employee')->get()->toArray());
        // veo que el usuario que venía en la url está ya seleccionado
        $I->seeOptionIsSelected('#employee_id', 'Trabajador 1 B1');
        
        // veo una lista con los empleados del proyecto
        $I->see('Trabajador 1 B1', 'option');
        $I->see('Trabajador 2 B2', 'option');
        
        // pero  no veo los trabajadores de otros proyectos
        $I->dontSee('Trabajador 3 B1', 'option');
        $I->dontSee('Trabajador 4 B2', 'option');
        
        // veo en un select los tipos de novedades
        $I->see('Licencia No Remunerada', 'option');
        $I->see('Permiso No Remunerado', 'option');
        $I->see('Incapacidad Enfermedad Generar', 'option');
        $I->see('Incapacidad Accidente Trabajo', 'option');
        $I->see('Licencia Luto', 'option');
        $I->see('Licencia Peternidad', 'option');

        // lleno el formulario con los datos necesarios y lo envío
        $I->submitForm('form', [
           'employee_id'    => '1',
           'novelty'        => '1',
           'reported_at'    => \Carbon\Carbon::now()->subDays(1)->toDateString(),
           'comment'        => 'el trabajador no vino a trabajar'
        ], 'Reportar');

        // veo que estoy en el index del módulo de reporte de novedades
        $I->seeCurrentUrlEquals('/noveltyReport/create');

        // veo mensaje de éxito en la operación
        $I->see('Novedad reportada exitosamente.', '.alert-success');
        
    }
}