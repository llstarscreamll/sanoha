<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\User;
use sanoha\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    private $faker;
    private $date;
    private $data = array();
    
    public function __construct()
    {
        $this->faker    = Faker::create();
        $this->date     = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12')->subMonth();
    }
    
    public function run()
    {
        $this->createWorkOrdersModulePermissions();
        $this->createRolesModulePermissions();
        $this->createEmployeesModulePermissions();
        $this->createUsersModulePermissions();
        $this->createActivityReportsModulePermissions();
        $this->createNoveltyReportModulePermissions();
        $this->createLogsModulePermissions();
        
        DB::table('permissions')->insert($this->data);
    }
    
     /**
     * Crea permisos para el módulo de roles
     */
    public function createWorkOrdersModulePermissions()
    {
        $this->data[] = [
            'name'           => 'workOrder.index',
            'display_name'   => 'Listar Ordenes de Trabajo',
            'description'    => 'Ver en una lista de todas las ordenes de trabajo registradas',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'workOrder.create',
            'display_name'   => 'Crear Orden de Trabajo',
            'description'    => 'Crear nuevas ordenes de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'workOrder.show',
            'display_name'   => 'Ver Orden de Trabajo',
            'description'    => 'Visalizar la información de ordenes de trabajo (sólo lectura)',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'workOrder.edit',
            'display_name'   => 'Actualizar Orden de Trabajo',
            'description'    => 'Actualiza la información de las ordenes de trabajo registradas',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'workOrder.destroy',
            'display_name'   => 'Eliminar Orden de Trabajo',
            'description'    => 'Eliminar las ordenes de trabajo registradas',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'workOrder.internal_accompanist_report_form',
            'display_name'   => 'Crear Reporte de Acompañante Interno',
            'description'    => 'Permite crear los reportes de los acompañantes internos de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'workOrder.mainReport',
            'display_name'   => 'Crear Reporte Principal',
            'description'    => 'Permite crear el reporte principal de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        /* Nuevos permisos añadidos 18-11-2015 */
        $this->data[] = [
            'name'           => 'workOrder.mainReportEdit',
            'display_name'   => 'Actualizar Reporte Principal',
            'description'    => 'Permite actualizar el reporte principal de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        $this->data[] = [
            'name'           => 'workOrder.mainReportDestroy',
            'display_name'   => 'Eliminar Reporte Principal',
            'description'    => 'Permite eliminar el reporte principal de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        $this->data[] = [
            'name'           => 'workOrder.internal_accompanist_report_edit_form',
            'display_name'   => 'Actualizar Reporte de Acompañante Interno',
            'description'    => 'Permite actualizar el reporte del acompañante interno de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        $this->data[] = [
            'name'           => 'workOrder.internal_accompanist_report_delete',
            'display_name'   => 'Eliminar Reporte de Acompañante Interno',
            'description'    => 'Permite eliminar el reporte del acompañante interno de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        $this->data[] = [
            'name'           => 'workOrder.vehicleMovementForm',
            'display_name'   => 'Registrar Salidas/Entradas del Vehículo de la Orden de Trabajo',
            'description'    => 'Permite registrar en que estado entra o sale de la empresa el vehículo relacionado a la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
    }
    
    /**
     * Crea los permisos para el módulo de los logs
     */
    public function createLogsModulePermissions()
    {
        $this->data[] = [
            'name'           => 'log.index',
            'display_name'   => 'Ver Lista de Logs de Usuarios',
            'description'    => 'Permite ver de forma cronológica los movimientos realizados por los usuarios en el sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
    }
    
    /**
     * Crea permisos para el módulo de roles
     */
    public function createRolesModulePermissions()
    {
        $this->data[] = [
            'name'           => 'roles.index',
            'display_name'   => 'Listar Roles',
            'description'    => 'Ver en una lista todos los roles del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'roles.create',
            'display_name'   => 'Crear Rol',
            'description'    => 'Crear nuevos roles',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'roles.show',
            'display_name'   => 'Ver Rol',
            'description'    => 'Visalizar la información de los roles (sólo lectura)',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'roles.edit',
            'display_name'   => 'Actualizar Rol',
            'description'    => 'Actualiza la información de los roles del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'roles.destroy',
            'display_name'   => 'Eliminar Rol',
            'description'    => 'Eliminar roles del sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
    }
    
        /**
     * Creo los permisos para el módulo de empleados
     */
    public function createEmployeesModulePermissions()
    {
        $this->data[] = [
            'name'           => 'employee.index',
            'display_name'   => 'Listar empleados',
            'description'    => 'Ver una lista de todos empleados del sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'employee.create',
            'display_name'   => 'Crear empleado',
            'description'    => 'Crear empleados del sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'employee.show',
            'display_name'   => 'Ver empleado',
            'description'    => 'Visualizar la información de un empleado (sólo lectura)',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'employee.edit',
            'display_name'   => 'Editar empleado',
            'description'    => 'Editar la información de un empleado',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'employee.destroy',
            'display_name'   => 'Eliminar empleado',
            'description'    => 'Eliminar empleados del sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'employee.status',
            'display_name'   => 'Activar o Desactivar Empleados',
            'description'    => 'Permite activar o desactivar a los empleados registrados',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
    }
    
    
    /**
     * Crea los permisos para el módulo de usuarios
     */ 
    public function createUsersModulePermissions()
    {
        $this->data[] = [
            'name'           => 'users.index',
            'display_name'   => 'Listar Usuarios',
            'description'    => 'Ver una lista de todos usuarios del sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'users.create',
            'display_name'   => 'Crear Usuario',
            'description'    => 'Crear usuarios del sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'users.show',
            'display_name'   => 'Ver Usuario',
            'description'    => 'Visualizar la información de un usuario (sólo lectura)',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'users.edit',
            'display_name'   => 'Editar Usuario',
            'description'    => 'Editar la información de un usuario',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
            
        $this->data[] = [
            'name'           => 'users.destroy',
            'display_name'   => 'Eliminar Usuario',
            'description'    => 'Eliminar usuarios del sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
    }
    
    /**
     * Crea permisos para el módulo de reporte de labores mineras
     */
    public function createActivityReportsModulePermissions()
    {
        $this->data[] = [
            'name'           => 'activityReport.index',
            'display_name'   => 'Ver Reportes de Actividades Reportadas',
            'description'    => 'Ver todos los reportes de las actividades o labores mineras registradas',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'activityReport.create',
            'display_name'   => 'Crear reporte de actividades mineras',
            'description'    => 'Registrar una labor minera para luego ser mostrada en los reportes',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'activityReport.show',
            'display_name'   => 'Ver detalles de actividad',
            'description'    => 'Ver los detalles de una actividad reportada en modo lectura.',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'activityReport.edit',
            'display_name'   => 'Editar la información de una actividad',
            'description'    => 'Editar la información registrada de una actividad minera reportada',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'activityReport.destroy',
            'display_name'   => 'Borrar Actividades',
            'description'    => 'Borrar actividades registradas en el sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'activityReport.assignCosts',
            'display_name'   => 'Asiganr Costos de Actividades',
            'description'    => 'Asiganr los precios de las actividades mineras registradas en el sistema',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'activityReport.individual',
            'display_name'   => 'Reporte de Registros Individuales',
            'description'    => 'Reporte con los registros de las actividades reportadas individualmente',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'activityReport.calendar',
            'display_name'   => 'Reporte en Calendario de Actividades Mineras Registradas',
            'description'    => 'La vista calendario mucho mas detallada de las actividades mineras registradas en el sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];

        $this->data[] = [
            'name'           => 'activityReport.newCreateForm',
            'display_name'   => 'Cargador Alternativo de Labores Mineras',
            'description'    => 'Le permite registrar actividades o labores mineras usando el formulario alternativo, el cual es más rápido, pues se pueden reportar varias actividades a la vez.',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

    }
    
    /**
     * Permisos para el módulo de reporte de Novedades
     */
    public function createNoveltyReportModulePermissions()
    {
        $this->data[] = [
            'name'           => 'noveltyReport.index',
            'display_name'   => 'Ver reporte en tabla de actividades mineras registradas',
            'description'    => 'Ver todos los reportes de las actividades o labores mineras registradas',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'noveltyReport.create',
            'display_name'   => 'Reportar actividades mineras',
            'description'    => 'Registrar una labor minera para luego ser mostrada en los reportes',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'noveltyReport.show',
            'display_name'   => 'Ver detalles de actividad minera registrada',
            'description'    => 'Ver los detalles de una actividad reportada en modo lectura.',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'noveltyReport.edit',
            'display_name'   => 'Editar la información de una actividad minera ya registrada',
            'description'    => 'Editar la información registrada de una actividad minera reportada',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'noveltyReport.destroy',
            'display_name'   => 'Borrar Actividades Mineras',
            'description'    => 'Borrar actividades registradas en el sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'noveltyReport.calendar',
            'display_name'   => 'Reporte en Calendario',
            'description'    => 'Ver reporte en calendario de todas las novedades registradas',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
    }
}
