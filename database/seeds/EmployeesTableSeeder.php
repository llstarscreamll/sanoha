<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        
        $data = [];

        $subCostCenters = sanoha\Models\SubCostCenter::all()->count();
        
        // create random employees
        for ($i = 1; $i <= 200; $i++) {
            
            $data[] = [
                'position_id'           =>      1,
                'sub_cost_center_id'    =>      $faker->numberBetween(1,$subCostCenters),
                'name'                  =>      $faker->firstName,
                'lastname'              =>      $faker->lastname,
                'identification_number' =>      '123456789',
                'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];

        }
        
        DB::table('employees')->insert($data);
    }
}
