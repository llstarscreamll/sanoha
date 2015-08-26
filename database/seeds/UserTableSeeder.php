<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use sanoha\Models\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        $data = [];
        
        DB::table('users')->delete();
        
        // create admin user
        $data[] = [
            'name'          =>  'Johan',
            'lastname'      =>  'Alvarez',
            'email'         =>  'llstarscreamll@hotmail.com',
            'password'      =>  bcrypt('78963'),
            'activated'     =>  1,
            'created_at'    =>  $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'    =>  $date->toDateTimeString(),
            'deleted_at'    =>  null
        ];
        
        // create random users
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'name'          =>    $faker->firstName,
                'lastname'      =>    $faker->lastname,
                'email'         =>    $faker->unique()->email,
                'password'      =>    bcrypt('74123'),
                'activated'     =>    $faker->randomElement(['0', '1']),
                'created_at'    =>    $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
                'updated_at'    =>    $date->toDateTimeString(),
                'deleted_at'    =>    null
            ];
        }
        
        DB::table('users')->delete();
        DB::table('users')->insert($data);
        User::where('email', '=', 'llstarscreamll@hotmail.com')->first()->attachRole(2); // administrador
        $subCostCenters = \sanoha\Models\SubCostCenter::all()->lists('id');
        User::where('email', '=', 'llstarscreamll@hotmail.com')->first()->subCostCenters()->sync($subCostCenters); // añado todos los centros de costo
    }
}
