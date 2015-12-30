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
        $this->data[] = [
            'name'           => 'activityReport.newCreateForm',
            'display_name'   => 'Cargador Alternativo de Labores Mineras',
            'description'    => 'Le permite registrar actividades o labores mineras usando el formulario alternativo, el cual es más rápido, pues se pueden reportar varias actividades a la vez.',
            'created_at'     =>  $this->date->addMinutes($this->faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'     =>  $this->date->toDateTimeString()
        ];

        DB::table('permissions')->insert($this->data);
    }
}
