<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
    
        /**
         * System data
         */ 
        $this->call('PermissionTableSeeder');
        $this->call('RoleTableSeeder');
        $this->call('CostCenterTableSeeder');
        $this->call('PositionsTableSeeder');
        $this->call('SubCostCenterTableSeeder');
        $this->call('MiningActivitiesTableSeeder');
        $this->call('NoveltiesTableSeeder');
        
        // lista real de los empleados mineros de la empresa
        $this->call('EmployeesTableSeeder');
        $this->call('UserTableSeeder'); // el usuario por defecto de la base de datos
        
        /**
         * Test data
         */
        $this->call('ActivityReportsTableSeeder');
        $this->call('NoveltyReportsTableSeeder');
        $this->call('VehiclesTableSeeder');
        
    }

}
