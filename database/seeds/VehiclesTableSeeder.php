<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class VehiclesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        $data = [];
        
        $data[] = [
            'plate'         => 'AAA111',
            'description'   => 'Descripción de automóvil uno',
            'passengers'    =>  5,
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString(),
            'deleted_at'    =>  null
        ];
        
        $data[] = [
            'plate'         => 'BBB222',
            'description'   => 'Descripción de automóvil dos',
            'passengers'    =>  4,
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString(),
            'deleted_at'    =>  null
        ];
        
        DB::table('vehicles')->insert($data);
    }
}
