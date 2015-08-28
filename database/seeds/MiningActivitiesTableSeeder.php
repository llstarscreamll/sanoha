<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\MiningActivity as Activity;

class MiningActivitiesTableSeeder extends Seeder
{
    public function run()
    {   
        $faker = Faker::create();
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        $data = [];
        
        $data[] = [
            'name'          =>      'Vagoneta de Carbón',
            'short_name'    =>      'VC',
            'maximum'       =>      '100',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Vagoneta de Roca',
            'short_name'    =>    'VR',
            'maximum'       =>      '15',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Puerta de Avance',
            'short_name'    =>    'PA',
            'maximum'       =>      '5',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Puerta de Refuerzo Guía',
            'short_name'    =>    'PRG',
            'maximum'       =>      '10',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Puerta de Refuerzo Inclinado',
            'short_name'    =>    'PRI',
            'maximum'       =>      '5',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Tacos',
            'short_name'    =>    'TAC',
            'maximum'       =>      '10',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Tramos de Carrilera',
            'short_name'    =>    'TRM',
            'maximum'       =>      '10',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Malacate',
            'short_name'    =>    'MLC',
            'maximum'       =>      '100',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Benda',
            'short_name'    =>    'BENDA',
            'maximum'       =>      '5',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Envasada',
            'short_name'    =>    'EVS',
            'maximum'       =>      '50',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Administración',
            'short_name'    =>    'ADM',
            'maximum'       =>      '1',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Cargue',
            'short_name'    =>    'CRG',
            'maximum'       =>      '10',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Cuadros',
            'short_name'    =>    'CDS',
            'maximum'       =>      '5',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Canastas',
            'short_name'    =>    'CNST',
            'maximum'       =>      '5',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Metros de Avance',
            'short_name'    =>    'MTA',
            'maximum'       =>      '3',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Metros de Ensache',
            'short_name'    =>    'MTDCH',
            'maximum'       =>      '10',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Metros de Despate',
            'short_name'    =>    'MTDPT',
            'maximum'       =>      '10',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
        $data[] = [
            'name'          =>    'Otros',
            'short_name'    =>    'OTROS',
            'maximum'       =>      '20',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        
    DB::table('mining_activities')->insert($data);    
    }
}
