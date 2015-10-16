<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\User;
use sanoha\Models\Permission;

class SeederUpdate extends Seeder
{
    private $faker;
    private $date;
    private $data = array();
    
    public function __construct()
    {
        $this->faker    = Faker::create();
        $this->date     = Carbon::now();
    }
    
    public function run()
    {
        $this->addPermissions();
        
        DB::table('permissions')->insert($this->data);
    }
    
     /**
     * Agrega registros a la tabla permisos segun vallan surgiendo
     */
    public function addPermissions()
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
            'created_at'     =>  $this->date->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'workOrder.mainReport',
            'display_name'   => 'Crear Reporte Principal',
            'description'    => 'Permite crear el reporte principal de la orden de trabajo',
            'created_at'     =>  $this->date->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'log.index',
            'display_name'   => 'Ver Lista de Logs de Usuarios',
            'description'    => 'Permite ver de forma cronológica los movimientos realizados por los usuarios en el sistema',
            'created_at'     =>  $this->date->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $this->data[] = [
            'name'           => 'employee.status',
            'display_name'   => 'Activar o Desactivar Empleados',
            'description'    => 'Permite activar o desactivar a los empleados registrados',
            'created_at'    =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $this->date->toDateTimeString()
        ];
    }
}
