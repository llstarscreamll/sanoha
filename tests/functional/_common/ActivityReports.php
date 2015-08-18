<?php namespace common;

use Faker\Factory           as Faker;
use Carbon\Carbon;

use \sanoha\Models\Employee as EmployeeModel;
use \sanoha\Models\User     as UserModel;

use sanoha\Models\ActivityReport as Report;

class ActivityReports
{
    /**
     * Crear algunos reportes de actividades empezando desde el inicio
     * del anterior mes hasta donde lo diga el parametro, por defecto
     * tres meses, para crear datos del mes pasado, del mes en curso
     * y del mes entrante
     * 
     * @param   $days   90 días => 3 meses
     */
    public function createRandomActivityReports($days = 90)
    {
        $faker = Faker::create();

        $date = date('Y-m-d');
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 14:24:12');
        $date = $date->subDays($days);
        $employees = \sanoha\Models\Employee::all()->count();
        $sub_cost_centers = \sanoha\Models\SubCostCenter::count();
        
        $data = [];
        
        for ($i=0; $i<$days; $i++) {
            
            for($j=0; $j<30; $j++){
                
                $data[] = [
                    'sub_cost_center_id'    =>      $faker->numberBetween(1,$sub_cost_centers),
                    'employee_id'           =>      $faker->numberBetween(1,$employees), // empleados creados en EmployeesTableSeeder
                    'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                    'quantity'              =>      $faker->numberBetween(1,50),
                    'price'                 =>      $faker->numberBetween(5000,25000),
                    'reported_by'           =>      $faker->numberBetween(1,3),
                    'reported_at'           =>      $date->toDateTimeString(),
                    'created_at'            =>      $date->toDateTimeString(),
                    'updated_at'            =>      $date->toDateTimeString(),
                    'deleted_at'            =>      null
                ];
                
            }
            
            $date = $date->addDay();
        }
        
        \DB::table('activity_reports')->insert($data);
    }
    
    /**
     * Creo 3 labores mineras reportadas para el día de ayer
     * 
     */ 
    public function createYesterdayActivities()
    {
        $faker = Faker::create();
        $report_date = \Carbon\Carbon::now()->subDays(1);
        
        $data[] = [
            'sub_cost_center_id'    =>      1,
            'employee_id'           =>      1, // Trabajador 1 B1
            'mining_activity_id'    =>      1, // Avance de roca
            'quantity'              =>      5,
            'price'                 =>      25000,
            'reported_by'           =>      1, // Travis Orbin
            'reported_at'           =>      $report_date->toDateTimeString(),
            'created_at'            =>      $report_date->toDateTimeString(),
            'updated_at'            =>      $report_date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>      2,
            'employee_id'           =>      2, // Trabajador 2 B2
            'mining_activity_id'    =>      2, // Embasado
            'quantity'              =>      2,
            'price'                 =>      10000,
            'reported_by'           =>      1, // Travis Orbin
            'reported_at'           =>      $report_date->toDateTimeString(),
            'created_at'            =>      $report_date->toDateTimeString(),
            'updated_at'            =>      $report_date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'sub_cost_center_id'    =>      1,
            'employee_id'           =>      2, // Trabajador 2 B2
            'mining_activity_id'    =>      3, // Malacate
            'quantity'              =>      4,
            'price'                 =>      12000,
            'reported_by'           =>      1, // Travis Orbin
            'reported_at'           =>      $report_date->toDateTimeString(),
            'created_at'            =>      $report_date->toDateTimeString(),
            'updated_at'            =>      $report_date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        \DB::table('activity_reports')->insert($data);
    }
}
