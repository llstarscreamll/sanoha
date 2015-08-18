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

class AccessLinksCest
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
        $this->user->subCostCenters()->sync([1,2,3,4]); // estos son los id's de los subcentros de los primeros dos proyectos o centros de costo, sanoha y beteitiva
        
        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    
    public function seeAccessLinks(FunctionalTester $I)
    {
        $I->am('supervisor de minas');
        $I->wantTo('ver los links de acceso a los reportes de novedades de mis proyectos');
        
        // estoy en el home
        $I->amOnPage('/home');
        
        // veo que están listados los links de acceso al módulo de reporte de novedades
        // de los proyectos que tengo asignados
        $I->see('Proyecto Beteitiva', '#noveltyReports ul li');
        $I->see('Proyecto Sanoha', '#noveltyReports ul li');
        
        // no veo los que no tengo asignados
        $I->dontSee('Proyecto Cazadero', '#noveltyReports ul li');
        $I->dontSee('Proyecto Curital', '#noveltyReports ul li');
        $I->dontSee('Proyecto Escalera', '#noveltyReports ul li');
        $I->dontSee('Proyecto Pinos', '#noveltyReports ul li');
        
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // veo que estoy en el index del módulo de reporte de novedades
        $I->seeCurrentUrlEquals('/noveltyReport');
        
        // veo el título de la página
        $I->see('Reportes de Novedad', 'h1');
        
        // veo el nombre del proyecto que seleccioné
        $I->see('Proyecto Beteitiva', 'th');
    }
}