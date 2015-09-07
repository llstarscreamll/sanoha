<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class NoveltiesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        $data = [];
        
        $data[] = [
        'name'          => 'Licencia No Remunerada',
        'short_name'    => 'LNR',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Permiso No Remunerado',
        'short_name'    => 'PNR',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Incapacidad Enfermedad Generar',
        'short_name'    => 'IEG',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'          => 'Incapacidad Accidente Trabajo',
        'short_name'    => 'IAT',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'         => 'Licencia Luto',
        'short_name'   => 'LL',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];

        $data[] = [
        'name'         => 'Licencia Peternidad',
        'short_name'   => 'LP',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'         => 'Renunció',
        'short_name'   => 'R',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        $data[] = [
        'name'         => 'Vacaciones',
        'short_name'   => 'V',
        'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
        'updated_at'    =>  $date->toDateTimeString()
        ];
        
        DB::table('novelties')->delete();
        DB::table('novelties')->insert($data);
    }
}
