<?php namespace common;

use Faker\Factory as Faker;
use Carbon\Carbon;

class CostCenters
{
    
    /**
     * Crea centros de costo
     */ 
    public function createCostCenters()
    {
        $faker = Faker::create();
        
        $date = Carbon::now()->subDays(2);
        $data = [];
        
        $data[] = [
        'name'         => 'Proyecto Beteitiva',
        'short_name'   => 'beteitiva',
        'description'  => 'La mina Beteitiva',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];

        $data[] = [
        'name'         => 'Proyecto Sanoha',
        'short_name'   => 'sanoha',
        'description'  => 'La mina Sanoha',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'         => 'Proyecto Cazadero',
        'short_name'   => 'cazadero',
        'description'  => 'La mina Cazadero',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'         => 'Proyecto Curital',
        'short_name'   => 'curital',
        'description'  => 'La mina Curital',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'         => 'Proyecto Escalera',
        'short_name'   => 'escalera',
        'description'  => 'La mina Escalera',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'         => 'Proyecto Pinos',
        'short_name'   => 'pinos',
        'description'  => 'La mina Pinos',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        \DB::table('cost_centers')->insert($data);
    }
    
}