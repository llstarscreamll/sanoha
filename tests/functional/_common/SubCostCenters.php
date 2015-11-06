<?php namespace common;

use sanoha\Models\CostCenter as CostCenterModel;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SubCostCenters
{
     /**
     * Crea subcentros de costos
     */ 
    public function createSubCostCenters()
    {
        $faker = Faker::create();
        
        $date = Carbon::now()->subDays(2);
        $data = [];
        
        $costCenters = CostCenterModel::all();

        foreach($costCenters as $costCenter){
            
            $data[] = [
                'cost_center_id'    =>      $costCenter->id,
                'name'              =>      'Bocamina 1',
                'short_name'        =>      'B1',
                'description'       =>      'Descripción de la bocamina 1',
                'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'        =>      $date->toDateTimeString()
            ];
            
            $data[] = [
                'cost_center_id'    =>      $costCenter->id,
                'name'              =>      'Bocamina 2',
                'short_name'        =>      'B2',
                'description'       =>      'Descripción de la bocamina 2',
                'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'        =>      $date->toDateTimeString()
            ];
            
        }
        
        \DB::table('sub_cost_centers')->insert($data);
    }
    
}