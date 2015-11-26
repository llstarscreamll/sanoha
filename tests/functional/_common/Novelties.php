<?php
namespace common;

use Faker\Factory as Faker;
use Carbon\Carbon;

class Novelties
{
    /**
     * Crea actividades mineras
     */
    public function createNoveltiesKinds()
    {
        $faker = Faker::create();
        
        $date = Carbon::now()->subDays(2);
        $data = [];
        
        $data[] = [
        'name'          => 'Licencia No Remunerada',
        'short_name'    => 'LNR',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString(),
        'deleted_at'    =>  null
        ];
        
        $data[] = [
        'name'          => 'Permiso No Remunerado',
        'short_name'    => 'PNR',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString(),
        'deleted_at'    =>  null
        ];
        
        $data[] = [
        'name'          => 'Incapacidad Enfermedad Generar',
        'short_name'    => 'IEG',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString(),
        'deleted_at'    =>  null
        ];
        
        $data[] = [
        'name'          => 'Incapacidad Accidente Trabajo',
        'short_name'    => 'IAT',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString(),
        'deleted_at'    =>  null
        ];
        
        $data[] = [
        'name'         => 'Licencia Luto',
        'short_name'   => 'LL',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString(),
        'deleted_at'    =>  null
        ];

        $data[] = [
        'name'         => 'Licencia Peternidad',
        'short_name'   => 'LP',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString(),
        'deleted_at'    =>  null
        ];
        
        \DB::table('novelties')->delete();
        \DB::table('novelties')->insert($data);
    }
}
