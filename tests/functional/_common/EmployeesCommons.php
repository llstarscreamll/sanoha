<?php namespace Permissions\_common;

use Faker\Factory as Faker;
use sanoha\Models\Employee;


class PermissionCommons
{
    /**
     * Create some activity reports
     */
    public function haveEmployees()
    {
        /*
        $faker = Faker::create();
        
        $date = !$date ? date('Y-m-d') : $date;
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 14:24:12');
        $date = $date->subDays($days);
        
        $data = [];
        
        // create random employees
        for ($i = 0; $i < 3; $i++) {
            $data[] = [
                'position_id'           =>    1,
                'cost_center_id'        =>    1,
                'name'                  =>    $faker->firstName,
                'lastname'              =>    $faker->lastname,
                'identification_number' =>    '123456789',
                'created_at'            =>      $date->addMinutes($faker->numberBetween(1,2))->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
            
            $data[] = [
                'position_id'           =>    1,
                'cost_center_id'        =>    2,
                'name'                  =>    $faker->firstName,
                'lastname'              =>    $faker->lastname,
                'identification_number' =>    '987654321',
                'created_at'            =>      $date->addMinutes($faker->numberBetween(1,2))->toDateTimeString(),
                'updated_at'            =>      $date->toDateTimeString(),
                'deleted_at'            =>      null
            ];
        }
        
        \DB::table('employees')->delete();
        \DB::table('employees')->insert($data);
        */
    }
    
}
