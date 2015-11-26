<?php
namespace common;

use Faker\Factory as Faker;
use Carbon\Carbon;

class Areas
{
    /**
     * Crea areas
     */
    public static function createAreas()
    {
        $faker = Faker::create();
        
        $date = Carbon::now()->subDays(2);
        $data = [];
        
        $data[] = [
        'name'          => 'Minas',
        'short_name'    => 'minas',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];

        $data[] = [
        'name'          => 'Ambiental',
        'short_name'    => 'ambiental',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Operativa',
        'short_name'    => 'operativa',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Administrativa',
        'short_name'    => 'administrativa',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        \DB::table('areas')->insert($data);
    }
}
