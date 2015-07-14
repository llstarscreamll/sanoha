<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\Employee;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        
        $data = [];

        // create random employees
        for ($i = 0; $i < 10; $i++) {
            
            $data[] = [
                'position_id'           =>    1,
                'cost_center_id'        =>    $faker->numberBetween(1,6), // centos de costo creados en CostCenterTableSeeder
                'name'                  =>    $faker->firstName,
                'lastname'              =>    $faker->lastname,
                'identification_number' =>    '123456789',
                'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'position_id'           =>    1,
                'cost_center_id'        =>    $faker->numberBetween(1,6), // centos de costo creados en CostCenterTableSeeder
                'name'                  =>    $faker->firstName,
                'lastname'              =>    $faker->lastname,
                'identification_number' =>    '987654321',
                'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
        }
        
        DB::table('employees')->delete();
        DB::table('employees')->insert($data);
    }
}
