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
        
        // --------------------------------
        // beteitiva
        // --------------------------------
        $data[] = [
            'cost_center_id'    =>      1, // beteitiva
            'name'              =>      'Bocamina 1',
            'short_name'        =>      'B1',
            'description'       =>      'Beteitiva Bocamina 1',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];

        $data[] = [
            'cost_center_id'    =>      1, // beteitiva
            'name'              =>      'Bocamina 2',
            'short_name'        =>      'B2',
            'description'       =>      'Beteitiva Bocamina 2',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];

        $data[] = [
            'cost_center_id'    =>      1, // beteitiva
            'name'              =>      'Bocamina 3',
            'short_name'        =>      'B3',
            'description'       =>      'Beteitiva Bocamina 3',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];


        // --------------------------------
        // Cazadero
        // --------------------------------
        $data[] = [
            'cost_center_id'    =>      2, // Cazadero
            'name'              =>      'Bocamina 1',
            'short_name'        =>      'B1',
            'description'       =>      'Cazadero Bocamina 1',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];

        // --------------------------------
        // Curital
        // --------------------------------
        $data[] = [
            'cost_center_id'    =>      3, // Curital
            'name'              =>      'Bocamina 1',
            'short_name'        =>      'B1',
            'description'       =>      'Curital Bocamina 1',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];

        // --------------------------------
        // Escalera
        // --------------------------------
        $data[] = [
            'cost_center_id'    =>      4, // Escalera
            'name'              =>      'Bocamina 1',
            'short_name'        =>      'B1',
            'description'       =>      'Escalera Bocamina 1',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];

        $data[] = [
            'cost_center_id'    =>      4, // Escalera
            'name'              =>      'Bocamina 4',
            'short_name'        =>      'B4',
            'description'       =>      'Escalera Bocamina 4',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];

        // --------------------------------
        // Pinos
        // --------------------------------
        $data[] = [
            'cost_center_id'    =>      5, // Pinos
            'name'              =>      'Bocamina 1',
            'short_name'        =>      'B1',
            'description'       =>      'Pinos Bocamina 1',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];

        // --------------------------------
        // Sanoha
        // --------------------------------
        $data[] = [
            'cost_center_id'    =>      6, // Sanoha
            'name'              =>      'Bocamina 1',
            'short_name'        =>      'B1',
            'description'       =>      'Sanoha Bocamina 1',
            'created_at'        =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'        =>      $date->toDateTimeString()
        ];

        DB::table('sub_cost_centers')->insert($data);
    }
}
