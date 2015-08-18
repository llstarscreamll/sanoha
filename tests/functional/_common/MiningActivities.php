<?php namespace common;

class MiningActivities
{
    /**
     * Crea actividades mineras
     */
    public function createMiningActivities()
    {
        $data = [];
        
        // en orden alfabético    
        $data[] = [
            'name'          =>  'Avance Roca',
            'short_name'    =>  'AR',
            'maximum'       =>  10,
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
            ];
            
        $data[] = [
            'name'          =>  'Embasado',
            'short_name'    =>  'E',
            'maximum'       =>  5,
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
            ];
            
        $data[] = [
            'name'          =>  'Malacate',
            'short_name'    =>  'M',
            'maximum'       =>  4,
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
            ];
        
        $data[] = [
            'name'          =>  'Picado',
            'short_name'    =>  'P',
            'maximum'       =>  3,
            'created_at'    =>  date('Y-m-d H:i:s'),
            'updated_at'    =>  date('Y-m-d H:i:s'),
            'deleted_at'    =>  null
            ];
        
        \DB::table('mining_activities')->insert($data);
    }
}