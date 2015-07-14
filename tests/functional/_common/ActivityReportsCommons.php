<?php namespace ActivityReports\_common;

use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\ActivityReport as Report;

class ActivityReportsCommons
{
    /**
     * Crear algunos reportes de actividades empezando desde el inicio
     * del anterior mes hasta donde lo diga el parametro, por defecto
     * tres meses, para crear datos del mes pasado, del mes en curso
     * y del mes entrante
     * 
     * @param   $days   90 días => 3 meses
     */
    public function haveActivityReports($date = null, $days = 90)
    {
        $faker = Faker::create();
        
        $date = !$date ? date('Y-m-d') : $date;
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 14:24:12');
        $date = $date->subDays($days);
        
        $data = [];
        
        for ($i=0; $i<$days; $i++) {
            
            $data[] = [
                'employee_id'           =>      1, // empleados creados en UserCommons, este empleado es de Beteitiva
                'mining_activity_id'    =>      1,
                'quantity'              =>      2,
                'price'                 =>      2000,
                'reported_by'           =>      $faker->numberBetween(1,10),
                'created_at'            =>      $date->addSeconds($faker->numberBetween(1,2))->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      2, // empleados creados en UserCommons, empleado de Sanoha
                'mining_activity_id'    =>      3,
                'quantity'              =>      3,
                'price'                 =>      3000,
                'reported_by'           =>      $faker->numberBetween(1,10),
                'created_at'            =>      $date->addSeconds($faker->numberBetween(1,2))->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(3,12),
                'mining_activity_id'    =>      3,
                'quantity'              =>      4,
                'price'                 =>      1000,
                'reported_by'           =>      $faker->numberBetween(1,10),
                'created_at'            =>      $date->addSeconds($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $date = $date->addDay();
        }
        
        \DB::table('activity_reports')->delete();
        \DB::table('activity_reports')->insert($data);
    }
    
}
