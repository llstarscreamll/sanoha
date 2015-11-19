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
        $this->createNewPermissions();
    }

    public function createNewPermissions()
    {
        
    }
}
