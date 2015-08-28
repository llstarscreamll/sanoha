<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\User;
use sanoha\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        $data = [];
        
        // create permmisions to roles module
        $data[] = [
            'name'           => 'roles.index',
            'display_name'   => 'Listar Roles',
            'description'    => 'Ver en una lista todos los roles del sistema',
            'created_at'     =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $date->toDateTimeString()
        ];
            
        $data[] = [
            'name'           => 'roles.create',
            'display_name'   => 'Crear Rol',
            'description'    => 'Crear nuevos roles',
            'created_at'     =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $date->toDateTimeString()
        ];
            
        $data[] = [
            'name'           => 'roles.show',
            'display_name'   => 'Ver Rol',
            'description'    => 'Visalizar la información de los roles (sólo lectura)',
            'created_at'     =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $date->toDateTimeString()
        ];
            
        $data[] = [
        'name'           => 'roles.edit',
        'display_name'   => 'Actualizar Rol',
        'description'    => 'Actualiza la información de los roles del sistema',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'roles.destroy',
            'display_name'   => 'Eliminar Rol',
            'description'    => 'Eliminar roles del sistema',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        // create permissions to users module
        $data[] = [
            'name'           => 'users.index',
            'display_name'   => 'Listar Usuarios',
            'description'    => 'Ver una lista de todos usuarios del sistema',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'users.create',
            'display_name'   => 'Crear Usuario',
            'description'    => 'Crear usuarios del sistema',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
            
        $data[] = [
            'name'           => 'users.show',
            'display_name'   => 'Ver Usuario',
            'description'    => 'Visualizar la información de un usuario (sólo lectura)',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
            
        $data[] = [
            'name'           => 'users.edit',
            'display_name'   => 'Editar Usuario',
            'description'    => 'Editar la información de un usuario',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
            
        $data[] = [
            'name'           => 'users.destroy',
            'display_name'   => 'Eliminar Usuario',
            'description'    => 'Eliminar usuarios del sistema',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        
        // -----------------------------------------------------
        // Permisos para el módulo de reporte de labores mineras
        // -----------------------------------------------------
        $data[] = [
            'name'           => 'activityReport.index',
            'display_name'   => 'Ver reporte en tabla de actividades mineras registradas',
            'description'    => 'Ver todos los reportes de las actividades o labores mineras registradas',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'activityReport.create',
            'display_name'   => 'Reportar actividades mineras',
            'description'    => 'Registrar una labor minera para luego ser mostrada en los reportes',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'activityReport.show',
            'display_name'   => 'Ver detalles de actividad minera registrada',
            'description'    => 'Ver los detalles de una actividad reportada en modo lectura.',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'activityReport.edit',
            'display_name'   => 'Editar la información de una actividad minera ya registrada',
            'description'    => 'Editar la información registrada de una actividad minera reportada',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'activityReport.destroy',
            'display_name'   => 'Borrar Actividades Mineras',
            'description'    => 'Borrar actividades registradas en el sistema',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'activityReport.assignCosts',
            'display_name'   => 'Asignar Costos de Actividades Mineras',
            'description'    => 'Asignar los precios de las actividades mineras registradas en el sistema',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'activityReport.calendar',
            'display_name'   => 'Reporte en Calendario de Actividades Mineras Registradas',
            'description'    => 'La vista calendario mucho mas detallada de las actividades mineras registradas en el sistema',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
                
        $data[] = [
            'name'           => 'activityReport.individual',
            'display_name'   => 'Reporte de Registros Individuales',
            'description'    => 'Reporte con los registros de las actividades reportadas individualmente',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        // -----------------------------------------------------
        // Permisos para el módulo de reporte de Novedades
        // -----------------------------------------------------
        $data[] = [
            'name'           => 'noveltyReport.index',
            'display_name'   => 'Ver reporte en tabla de actividades mineras registradas',
            'description'    => 'Ver todos los reportes de las actividades o labores mineras registradas',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'noveltyReport.create',
            'display_name'   => 'Reportar novedades mineras',
            'description'    => 'Registrar una labor minera para luego ser mostrada en los reportes',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'noveltyReport.show',
            'display_name'   => 'Ver detalles de novedad minera registrada',
            'description'    => 'Ver los detalles de una novedad reportada en modo lectura.',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'noveltyReport.edit',
            'display_name'   => 'Editar la información de una novedad minera ya registrada',
            'description'    => 'Editar la información registrada de una novedad minera reportada',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'noveltyReport.destroy',
            'display_name'   => 'Borrar novedades Mineras',
            'description'    => 'Borrar novedades registradas en el sistema',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'noveltyReport.calendar',
            'display_name'   => 'Ver Reporte en Calendario de Novedades',
            'description'    => 'Ver un reporte en calendario de las novedades registradas',
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString()
        ];
        
        DB::table('permissions')->insert($data);
    }
}
