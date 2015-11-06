<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\User;
use sanoha\Models\Permission;

class SeederUpdate extends Seeder
{
    private $faker;
    private $date;
    private $data = array();
    
    public function __construct()
    {
        $this->faker    = Faker::create();
        $this->date     = Carbon::now();
    }
    
    public function run()
    {
        $this->createAreas();
    }
    
     /**
     * Agrega registros a la tabla permisos segun vallan surgiendo
     */
    public function createAreas()
    {
        $faker = Faker::create();
        
        $date = Carbon::now()->subDays(2);
        $data = [];
        
        $data[] = [
        'name'          => 'Minas',
        'short_name'    => 'minas',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];

        $data[] = [
        'name'          => 'Ambiental',
        'short_name'    => 'ambiental',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Operativa',
        'short_name'    => 'operativa',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Administrativa',
        'short_name'    => 'administrativa',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        \DB::table('areas')->insert($data);
    }
}
