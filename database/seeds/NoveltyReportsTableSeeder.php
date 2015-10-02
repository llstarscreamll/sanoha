<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\ActivityReport as Report;

class NoveltyReportsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $days = 100;
        $date = null;
        
        $date = !$date ? date('Y-m-d') : $date;
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 14:24:12');
        $date = $date->subDays($days/2);
        $employees = \sanoha\Models\Employee::all()->count();
        $sub_cost_centers = \sanoha\Models\SubCostCenter::count();
        $novelties = \sanoha\Models\Novelty::all()->count();
        
        $data = [];
        
        for ($i=0; $i<$days; $i++) {
            
            for($j=0; $j<10; $j++){
                
                $data[] = [
                    'sub_cost_center_id'    =>      $faker->numberBetween(1,$sub_cost_centers),
                    'employee_id'           =>      $faker->numberBetween(1,$employees), // empleados creados en EmployeesTableSeeder
                    'novelty_id'            =>      $faker->numberBetween(1,$novelties), // actividades creadas en MiningActivitiesTableSeeder
                    'comment'               =>      $faker->numberBetween(1,50),
                    'reported_at'           =>      $date->toDateTimeString(),
                    'created_at'            =>      $date->toDateTimeString(),
                    'updated_at'            =>      $date->toDateTimeString(),
                    'deleted_at'            =>      null
                ];
                
            }
            
            $date = $date->addDay();
        }
        
        \DB::table('novelty_reports')->insert($data);
    }
}
