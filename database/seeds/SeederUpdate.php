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
        $data[] = [
            'name'                  =>      'Horas Laboradas',
            'short_name'            =>      'HORAS',
            'maximum'               =>      '15',
            'created_at'            =>      $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $this->date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        DB::table('mining_activities')->insert($data); 
    }
}
