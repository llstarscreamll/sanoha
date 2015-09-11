<?php namespace common;

use Faker\Factory as Faker;
use Carbon\Carbon;

class Permissions
{
    private $faker;
    private $date;
    private $data = array();
    
    public function __construct()
    {
        $this->faker    = Faker::create();
        $this->date     = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12')->subMonth();
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
        'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'roles.destroy',
            'display_name'   => 'Eliminar Rol',
            'description'    => 'Eliminar roles del sistema',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
        
        \DB::table('permissions')->insert($this->data);
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
        
        \DB::table('permissions')->insert($this->data);
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

        \DB::table('permissions')->insert($this->data);
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
        
        \DB::table('permissions')->insert($this->data);
    }
}
