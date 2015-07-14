<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\ActivityReport as Report;

class ActivityReportsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $days = 90; // se crearán datos para 90 días
        $date = null;
        
        $date = !$date ? date('Y-m-d') : $date;
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 14:24:12');
        $date = $date->subDays($days/2);
        
        $data = [];
        
        for ($i=0; $i<$days; $i++) {
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'employee_id'           =>      $faker->numberBetween(1,20), // empleados creados en EmployeesTableSeeder
                'mining_activity_id'    =>      $faker->numberBetween(1,18), // actividades creadas en MiningActivitiesTableSeeder
                'quantity'              =>      $faker->numberBetween(1,50),
                'price'                 =>      $faker->numberBetween(5000,25000),
                'reported_by'           =>      $faker->numberBetween(1,3),
                'created_at'            =>      $date->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $date = $date->addDay();
        }
        
        \DB::table('activity_reports')->delete();
        \DB::table('activity_reports')->insert($data);
    }
}
