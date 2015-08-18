<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\CostCenter;

class SubCostCenterTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        $data = [];
        
        $costCenters = \sanoha\Models\CostCenter::all();

        foreach($costCenters as $costCenter){
            
            $data[] = [
                'cost_center_id'    =>      $costCenter->id,
                'name'              =>      'Bocamina 1',
                'short_name'        =>      'B1',
                'description'       =>      'Descripción de la bocamina',
                'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'        =>      $date->toDateTimeString()
            ];
            
            $data[] = [
                'cost_center_id'    =>      $costCenter->id,
                'name'              =>      'Bocamina 2',
                'short_name'        =>      'B2',
                'description'       =>      'Descripción de la bocamina',
                'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'        =>      $date->toDateTimeString()
            ];
            
            if($faker->numberBetween(1,2) === 2){
             
                $data[] = [
                    'cost_center_id'    =>      $costCenter->id,
                    'name'              =>      'Bocamina 3',
                    'short_name'        =>      'B3',
                    'description'       =>      'Descripción de la bocamina',
                    'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                    'updated_at'        =>      $date->toDateTimeString()
                ]; 

            }
            
            if($faker->numberBetween(1,2) === 2){
             
                $data[] = [
                    'cost_center_id'    =>      $costCenter->id,
                    'name'              =>      'Bocamina 4',
                    'short_name'        =>      'B4',
                    'description'       =>      'Descripción de la bocamina',
                    'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                    'updated_at'        =>      $date->toDateTimeString()
                ]; 

            }   
        }
        
        DB::table('sub_cost_centers')->delete();
        DB::table('sub_cost_centers')->insert($data);
    }
}
