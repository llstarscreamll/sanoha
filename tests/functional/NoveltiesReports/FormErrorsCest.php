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

class FormErrorsCest
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
     * Pruebo los mensajes de error en la búsqueda de novedades
     */
    public function searchErrors(FunctionalTester $I)
    {
        $I->am('un ingheniero del área tecnica');
        $I->wantTo('ver mensages al buscar una novedad');
        
        // estoy en el home
        $I->amOnPage('/home');
          
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        $I->seeCurrentUrlEquals('/noveltyReport');
        
        /* -- Campos vacíos, nada pasa, pueden estar vacíos --  */
        $I->submitForm('form[name=search]', [
            'from'  =>  '',
            'to'    =>  '',
            'find'  =>  ''
        ]);
        
        $I->dontSeeElement('div', ['class' => 'alert alert-danger alert-dismissible']);
        
        /* -- Formatos inválidos --  */
        $I->submitForm('form[name=search]', [
            'from'  =>  'sdf456',
            'to'    =>  'asdf345345',
            'find'  =>  '$#%&%'
        ]);
        
        $I->see('La fecha de inicio del filtro tiene un formato inválido.', '.text-danger');
        $I->see('La fecha de fin del filtro tiene un formato inválido.', '.text-danger');
        $I->see('Sólo puedes digitar letras, números y/o espacios.', '.text-danger');
        
        /* -- La fecha final es inferior a la inicial --  */
        $I->submitForm('form[name=search]', [
            'from'  =>  '2015-08-20',
            'to'    =>  '2015-08-01',
            'find'  =>  'alejandro'
        ]);
        
        $I->see('La fecha de fin debe ser mas reciente que la de inicio.', '.text-danger');
    }

    /**
     * Pruebo los mensages de error en al reportar una novedad
     */ 
    public function createErrors(FunctionalTester $I)
    {
        $I->am('un ingheniero del área tecnica');
        $I->wantTo('ver mensages al reportar una novedad');
        
        // estoy en el home
        $I->amOnPage('/home');
          
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // voy a la página de reporte de novedad con un empleado seleccionado
        $I->amOnPage('/noveltyReport/create');
        
        /* -- Campos vacíos --  */
        $I->submitForm('form', [
            'employee_id'    => '',
            'novelty_id'        => '',
            'reported_at'    => '',
            'comment'        => ''
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('Selecciona un trabajador de la lista.', '.text-danger');
        $I->see('Debes seleccionar la novedad que vas a reportar.', '.text-danger');
        $I->see('Selecciona la fecha en que se presentó la novedad.', '.text-danger');
        
         /* -- Formatos inválidos --  */
        $I->submitForm('form', [
            'employee_id'    => 'dfg',
            'novelty_id'        => 'dfg',
            'reported_at'    => 'dfg',
            'comment'        => '/()$%'
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('Identificador de empleado inválido.', '.text-danger');
        $I->see('Identificador de tipo de novedad inválido.', '.text-danger');
        $I->see('La fecha tiene un formato inválido.', '.text-danger');
        $I->see('El comentario sólo debe contener letras y/o espacios.', '.text-danger');
        
        /* -- Campos que no existen en la base de datos --  */
        $date = \Carbon\Carbon::now();
        $I->submitForm('form', [
            'employee_id'    => 657,
            'novelty_id'     => 900,
            'reported_at'    => $date->toDateString(),
            'comment'        => ''
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('El trabajador no existe, trabajador inválido.', '.text-danger');
        $I->see('No existe el tipo novedad, novedad inválida.', '.text-danger');
        
        /* -- Fecha anterior al rango permitido -- */
        $I->submitForm('form', [
            'employee_id'    => 1,
            'novelty_id'     => 2,
            'reported_at'    => $date->startOfYear()->toDateString(),
            'comment'        => ''
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('La fecha debe ser depúes del '.\Carbon\Carbon::now()->subDays(30)->toDateString().'.', '.text-danger');
        
        /* -- Fecha posterior al rango permitido -- */
        $I->submitForm('form', [
            'employee_id'    => 1,
            'novelty_id'     => 2,
            'reported_at'    => $date->endOfYear()->toDateString(),
            'comment'        => ''
        ]);
        
        $I->seeCurrentUrlEquals('/noveltyReport/create');
        $I->seeFormHasErrors();
        
        $I->see('La fecha debe ser de antes del '.\Carbon\Carbon::now()->addDays(5)->toDateString().'.', '.text-danger');
    }
}