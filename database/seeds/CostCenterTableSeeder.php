<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\CostCenter;

class CostCenterTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        $data = [];
        
        $data[] = [
        'name'         => 'Proyecto Beteitiva',
        'short_name'   => 'beteitiva',
        'description'  => 'La mina Beteitiva',
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

        $data[] = [
        'name'         => 'Proyecto Sanoha',
        'short_name'   => 'sanoha',
        'description'  => 'La mina Sanoha',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        DB::table('cost_centers')->delete();
        DB::table('cost_centers')->insert($data);
    }
}
