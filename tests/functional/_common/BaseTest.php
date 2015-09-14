<?php   namespace common;

use \common\User                as UserCommons;
use \common\Roles               as RolesCommons;
use \common\Employees           as EmployeesCommons;
use \common\Novelties           as NoveltiesCommons;
use \common\CostCenters         as CostCentersCommons;
use \common\Permissions         as PermissionsCommons;
use \common\SubCostCenters      as SubCostCentersCommons;
use \common\ActivityReports     as ActivityReportsCommons;
use \common\MiningActivities    as MiningActivitiesCommons;

use \sanoha\Http\Requests\RoleFormRequest;

class BaseTest
{
    public $userCommons;
    public $rolesCommons;
    public $employeeCommons;
    public $roleFormRequest;
    public $noveltiesCommons;
    public $roleFormMessages;
    public $costCentersCommons;
    public $permissionsCommons;
    public $subCostCentersCommons;
    public $activityReportsCommons;
    public $miningActivitiesCommons;
    
    public $admin_user;
    
    /**
     * Dependencias de los test del módulo de empleados
     */
    public function employees()
    {
        // creo los permisos para el módulo de reporte de novedad
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createEmployeesModulePermissions();
        
        // cargo los datos base
        $this->createBasicData();
        
        // creo los empleados
        $this->employeeCommons = new EmployeesCommons;
        $this->employeeCommons->createMiningEmployees();
        
        $this->admin_user = $this->userCommons->adminUser;
    }
    
    /**
     * Dependencias de los test de reporte de novedades
     */
    public function noveltyReports()
    {
        // creo los permisos para el módulo de reporte de novedad
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createNoveltyReportModulePermissions();
        
        // cargo los datos base
        $this->createBasicData();
        
        // creo los empleados
        $this->employeeCommons = new EmployeesCommons;
        $this->employeeCommons->createMiningEmployees();
        
        // creo las novedades
        $this->noveltiesCommons = new NoveltiesCommons;
        $this->noveltiesCommons->createNoveltiesKinds();
        
        $this->admin_user = $this->userCommons->adminUser;
    }
    
    /**
     * Dependiencias para los test del módulo de usuarios
     */
    public function users()
    {
        // creo los permisos para el módulo de usuarios
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createUsersModulePermissions();
        
        // cargo los datos base
        $this->createBasicData();

        $this->admin_user = $this->userCommons->adminUser;
    }
    
    /**
     * Dependencias de los test de reporte de actividades mineras
     */ 
    public function activityReports()
    {
        // creo los permisos para el módulo de reporte de novedad
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createActivityReportsModulePermissions();
        
        $this->activityReportsCommons = new ActivityReportsCommons;
        
        // cargo los datos base
        $this->createBasicData();
        
        // creo los empleados
        $this->employeeCommons = new EmployeesCommons;
        $this->employeeCommons->createMiningEmployees();
        
        // creo actividades mineras
        $this->miningActivities = new MiningActivitiesCommons;
        $this->miningActivities->createMiningActivities();
        
        $this->admin_user = $this->userCommons->adminUser;
    }
    
    public function roles()
    {
        $this->roleFormRequest = new RoleFormRequest;
        $this->roleFormMessages = $this->roleFormRequest->messages();
        
        // creo los permisos para el módulo de roles
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createRolesModulePermissions();
        
        // cargo los datos base
        $this->createBasicData();

        $this->admin_user = $this->userCommons->adminUser;
    }
    
    /**
     * Creo los datos básicos que deben existir en la mayoría de los tests
     */ 
    private function createBasicData()
    {
        // creo los roles de usuario y añado todos los permisos al rol de administrador
        $this->rolesCommons = new RolesCommons;
        $this->rolesCommons->createBasicRoles();
                
        // creo el usuairo administrador
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();
        $this->userCommons->createUsers();
        
        // creo sub centros de costo
        $this->costCentersCommons = new CostCentersCommons;
        $this->costCentersCommons->createCostCenters();
        
        // creo centros de costo
        $this->subCostCentersCommons = new SubCostCentersCommons;
        $this->subCostCentersCommons->createSubCostCenters();
    }
}