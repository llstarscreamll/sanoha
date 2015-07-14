<?php

use Illuminate\Database\Seeder;
use sanoha\Models\Position;

class PositionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('positions')->delete();

        Position::create([
            'name'  =>  'Minero',
        ]);
        
        Position::create([
            'name'  =>  'Supervisor',
        ]);
    }
}
