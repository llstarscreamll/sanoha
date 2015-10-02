<?php

namespace common;

use Carbon\Carbon;
use Faker\Factory           as Faker;

class Vehicles
{
    /**
     * Create several users on storage, default 10 users
     *
     * @param int $num
     * @return void
     */
    public function createVehicles()
    {
        $faker = Faker::create();
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subDays(3);
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
        
        \DB::table('vehicles')->insert($data);
    }
    
}