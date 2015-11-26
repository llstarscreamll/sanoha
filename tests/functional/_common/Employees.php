<?php
namespace common;

use Faker\Factory as Faker;
use \sanoha\Models\Position as PositionModel;
use \sanoha\Models\SubCostCenter as SubCostCenterModel;

class Employees
{
    /**
     * Create several employees on storage, default 10 users
     *
     * @param int $num
     * @return void
     */
    public function createMiningEmployees()
    {
        $faker = Faker::create();
        $data = [];
        $subCostCenters = SubCostCenterModel::all();
        $count = 1;
        $bool = true;
        
        $date = \Carbon\Carbon::now()->subDays(2);
        $date = $date->subMonth();
        
        PositionModel::create([
            'name'  =>  'Minero'
            ]);
        PositionModel::create([
            'name'  =>  'Supervisor'
        ]);
        
        foreach ($subCostCenters as $subCostCenter) {
            $data[] = [
                'position_id'                   =>      1,
                'sub_cost_center_id'            =>      $subCostCenter->id,
                'name'                          =>      'Trabajador ' . $count,
                'lastname'                      =>      $subCostCenter->short_name,
                'identification_number'         =>      '1'.$count,
                'email'                         =>      'trabajador'.$count++.'@example.com',
                'status'                        =>      'enabled',
                'authorized_to_drive_vehicles'  =>      $bool,
                'created_at'                    =>      $date->addMinutes($faker->numberBetween(1, 10))->toDateTimeString(),
                'updated_at'                    =>      $date->toDateTimeString(),
                'deleted_at'                    =>      null
            ];
            
            $bool = false;
        }
        
        \DB::table('employees')->insert($data);
    }
}
