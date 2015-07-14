<?php namespace Permissions\_common;

use sanoha\Models\CostCenter;

class SystemCommons
{
    public function createCostCenters(){
        
        $data = [];
        
        $data[] = [
            'name'          => 'Proyecto Beteitiva',
            'short_name'    => 'beteitiva',
            'description'   => 'La mina Beteitiva',
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
        ];

        $data[] = [
            'name'          => 'Proyecto Sanoha',
            'short_name'    => 'sanoha',
            'description'   => 'La mina Sanoha',
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
        ];
        
        $data[] = [
            'name'          => 'Proyecto Cazadero',
            'short_name'    => 'cazadero',
            'description'   => 'La mina Cazadero',
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
        ];
        
        $data[] = [
            'name'          => 'Proyecto Pinos',
            'short_name'    => 'pinos',
            'description'   => 'La mina Pinos',
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
        ];

        \DB::table('cost_centers')->insert($data);
        
    }
    
}