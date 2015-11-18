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
        $this->createAreas();
        $this->createNewPermissions();
    }

    public function createNewPermissions()
    {
        $data[] = [
            'name'           => 'workOrder.index',
            'display_name'   => 'Listar Ordenes de Trabajo',
            'description'    => 'Ver en una lista de todas las ordenes de trabajo registradas',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $data[] = [
            'name'           => 'workOrder.create',
            'display_name'   => 'Crear Orden de Trabajo',
            'description'    => 'Crear nuevas ordenes de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $data[] = [
            'name'           => 'workOrder.show',
            'display_name'   => 'Ver Orden de Trabajo',
            'description'    => 'Visalizar la información de ordenes de trabajo (sólo lectura)',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
            
        $data[] = [
            'name'           => 'workOrder.edit',
            'display_name'   => 'Actualizar Orden de Trabajo',
            'description'    => 'Actualiza la información de las ordenes de trabajo registradas',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'workOrder.destroy',
            'display_name'   => 'Eliminar Orden de Trabajo',
            'description'    => 'Eliminar las ordenes de trabajo registradas',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'workOrder.internal_accompanist_report_form',
            'display_name'   => 'Crear Reporte de Acompañante Interno',
            'description'    => 'Permite crear los reportes de los acompañantes internos de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];
        
        $data[] = [
            'name'           => 'workOrder.mainReport',
            'display_name'   => 'Crear Reporte Principal',
            'description'    => 'Permite crear el reporte principal de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        /* Nuevos permisos añadidos 18-11-2015 */
        $data[] = [
            'name'           => 'workOrder.mainReportEdit',
            'display_name'   => 'Actualizar Reporte Principal',
            'description'    => 'Permite actualizar el reporte principal de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        $data[] = [
            'name'           => 'workOrder.mainReportDestroy',
            'display_name'   => 'Eliminar Reporte Principal',
            'description'    => 'Permite eliminar el reporte principal de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        $data[] = [
            'name'           => 'workOrder.internal_accompanist_report_edit_form',
            'display_name'   => 'Actualizar Reporte de Acompañante Interno',
            'description'    => 'Permite actualizar el reporte del acompañante interno de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        $data[] = [
            'name'           => 'workOrder.internal_accompanist_report_delete',
            'display_name'   => 'Eliminar Reporte de Acompañante Interno',
            'description'    => 'Permite eliminar el reporte del acompañante interno de la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        $data[] = [
            'name'           => 'workOrder.vehicleMovementForm',
            'display_name'   => 'Registrar Salidas/Entradas del Vehículo de la Orden de Trabajo',
            'description'    => 'Permite registrar en que estado entra o sale de la empresa el vehículo relacionado a la orden de trabajo',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        \DB::table('permissions')->insert($data);
    }
    
     /**
     * Agrega registros a la tabla permisos segun vallan surgiendo
     */
    public function createAreas()
    {
        $faker = Faker::create();
        
        $date = Carbon::now()->subDays(2);
        $data = [];
        
        $data[] = [
        'name'          => 'Minas',
        'short_name'    => 'minas',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];

        $data[] = [
        'name'          => 'Ambiental',
        'short_name'    => 'ambiental',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Operativa',
        'short_name'    => 'operativa',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Administrativa',
        'short_name'    => 'administrativa',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        \DB::table('areas')->insert($data);
    }
}
