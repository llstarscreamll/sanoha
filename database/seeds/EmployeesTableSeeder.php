<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 14:24:12');
        $date = $date->subMonth();
        
        $data = [];
        
        // --------------------
        // Beteitiva
        // Bocamina 1
        // --------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'AURELIO',
            'lastname'              =>      'CARDENAS ALBARRACIN',
            'identification_number' =>      '1004312',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'LUIS YOVANY',
            'lastname'              =>      'CRISTANCHO CAMACHO',
            'identification_number' =>      '74270769',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'BERNARDO',
            'lastname'              =>      'LEON',
            'identification_number' =>      '1004605',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'AULICES',
            'lastname'              =>      'MONTOYA NARANJO',
            'identification_number' =>      '74270767',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'JOSE HUMBERTO',
            'lastname'              =>      'ROJAS GIL',
            'identification_number' =>      '1004322',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'JOSE MAXIMINO',
            'lastname'              =>      'ROJAS GIL',
            'identification_number' =>      '1004395',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'TEODORO',
            'lastname'              =>      'SILVA BUITRAGO',
            'identification_number' =>      '4085928',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'MANUEL JOSE',
            'lastname'              =>      'VARGAS RINCON',
            'identification_number' =>      '7228921',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      1,
            'name'                  =>      'MIGUEL ENRIQUE',
            'lastname'              =>      'VERDUGO QUIROGA',
            'identification_number' =>      '74327143',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        // --------------------
        // Beteitiva
        // Bocamina 2
        // --------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      2,
            'name'                  =>      'JOSE ANTONIO',
            'lastname'              =>      'AGUILAR',
            'identification_number' =>      '41683323',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      2,
            'name'                  =>      'EUGENIO',
            'lastname'              =>      'BERDUGO ARISMENDY',
            'identification_number' =>      '19484872',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      2,
            'name'                  =>      'JUAN MANUEL',
            'lastname'              =>      'BERDUGO MARENTES',
            'identification_number' =>      '1058430740',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      2,
            'name'                  =>      'EULISES',
            'lastname'              =>      'LEON CELY',
            'identification_number' =>      '1004510',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      2,
            'name'                  =>      'MARIO',
            'lastname'              =>      'LEON CELY',
            'identification_number' =>      '1004577',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      2,
            'name'                  =>      'HELVIS GEILER',
            'lastname'              =>      'RINCON MONTAÑEZ',
            'identification_number' =>      '1048690105',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      2,
            'name'                  =>      'JOSE NICOMEDES',
            'lastname'              =>      'SANCHEZ ALONSO',
            'identification_number' =>      '1004453',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      2,
            'name'                  =>      'CARLOS',
            'lastname'              =>      'VARGAS MANCIPE',
            'identification_number' =>      '1004608',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        // --------------------
        // Beteitiva
        // Bocamina 3
        // --------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      3,
            'name'                  =>      'JOSE ANTONIO',
            'lastname'              =>      'FERNANDEZ PEREZ',
            'identification_number' =>      '1075651711',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      3,
            'name'                  =>      'CARLOS YOVANI',
            'lastname'              =>      'LEON GALLO',
            'identification_number' =>      '1004586',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      3,
            'name'                  =>      'AURELIO',
            'lastname'              =>      'FERNANDEZ ACERO',
            'identification_number' =>      '4206875',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      3,
            'name'                  =>      'LUIS ALFREDO',
            'lastname'              =>      'LEON CELY',
            'identification_number' =>      '1004559',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      3,
            'name'                  =>      'WILSON',
            'lastname'              =>      'VARGAS',
            'identification_number' =>      '1048690103',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      3,
            'name'                  =>      'MARIO ENRIQUE',
            'lastname'              =>      'SANCHEZ ALFONSO',
            'identification_number' =>      '7210973',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      3,
            'name'                  =>      'EUSTAQUIO',
            'lastname'              =>      'GOMEZ FUENTES',
            'identification_number' =>      '74083999',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        // --------------------
        // Cazadero
        // Bocamina 1
        // --------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      4,
            'name'                  =>      'HECTOR JULIO',
            'lastname'              =>      'RANGEL PICO',
            'identification_number' =>      '13702077',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        // --------------------
        // Curital
        // Bocamina 1
        // --------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'CRISTIANO BENITEZ',
            'name'                  =>      'LUIS HUMBERTO',
            'identification_number' =>      '74185176',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'ESTEPA SUA',
            'name'                  =>      'HILARIO',
            'identification_number' =>      '74321552',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'LOPEZ MALDONADO',
            'name'                  =>      'LUIS JAVIER',
            'identification_number' =>      '74374939',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'LOPEZ SANCHEZ',
            'name'                  =>      'CUSTODIO',
            'identification_number' =>      '6757754',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'MARQUEZ VEGA',
            'name'                  =>      'LUIS EDUARDO',
            'identification_number' =>      '9375129',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'RAMIREZ ROMERO',
            'name'                  =>      'PEDRO NEL',
            'identification_number' =>      '9375053',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'RIAÑO DIAZ',
            'name'                  =>      'DANIEL ROBERTO',
            'identification_number' =>      '79351444',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'RODRIGUEZ CRISTIANO',
            'name'                  =>      'LUIS',
            'identification_number' =>      '74321420',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'SANABRIA ARAQUE',
            'name'                  =>      'SAUL',
            'identification_number' =>      '13702160',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      5,
            'lastname'              =>      'VARGAS PINTO',
            'name'                  =>      'SILVESTRE',
            'identification_number' =>      '9524648',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        // --------------------
        // Escalera
        // Bocamina 1
        // --------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'AGUDELO SALAMANCA',
            'name'                  =>      'DANIEL',
            'identification_number' =>      '9510378',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'CARDENAS PRADA',
            'name'                  =>      'NOVAR',
            'identification_number' =>      '13560771',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'CASTAÑEDA CASTRO',
            'name'                  =>      'JOSE HUMBERTO',
            'identification_number' =>      '1177765',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'GOMEZ PARRA',
            'name'                  =>      'FLORENTINO',
            'identification_number' =>      '4272415',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'MANCIPE SILVA',
            'name'                  =>      'JOSE IGNACIO',
            'identification_number' =>      '4057639',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'OLARTE BAUTISTA',
            'name'                  =>      'EZEQUIEL',
            'identification_number' =>      '7924898',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'PARADA GUAUQUE',
            'name'                  =>      'ANCELMO',
            'identification_number' =>      '9534550',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'PUENTES ALFONSO',
            'name'                  =>      'TELESFORO',
            'identification_number' =>      '7214603',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'PULIDO PULIDO',
            'name'                  =>      'ARVENIO GUSTAVO',
            'identification_number' =>      '74270448',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'RUIZ SATIVA',
            'name'                  =>      'PEDRO ANTONIO',
            'identification_number' =>      '4122707',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      6,
            'lastname'              =>      'SANCHEZ RIVEROS',
            'name'                  =>      'CARLOS DANIEL',
            'identification_number' =>      '9590375',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        // --------------------
        // Escalera
        // Bocamina 4
        // --------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      7,
            'lastname'              =>      'BAUTISTA BERDUGO',
            'name'                  =>      'JOSE RAUL',
            'identification_number' =>      '13701190',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      7,
            'lastname'              =>      'CARDENAS PRADA',
            'name'                  =>      'RENE',
            'identification_number' =>      '1095458357',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      7,
            'lastname'              =>      'FUENTES FUENTES',
            'name'                  =>      'LUIS ADENARCO',
            'identification_number' =>      '1002681094',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      7,
            'lastname'              =>      'FUENTES PINZON',
            'name'                  =>      'ARIOSTO',
            'identification_number' =>      '5619918',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      7,
            'lastname'              =>      'QUIROGA VARGAS',
            'name'                  =>      'JOSE ANGEL',
            'identification_number' =>      '123456789',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      7,
            'lastname'              =>      'RINCON CARVAJAL',
            'name'                  =>      'VICTOR RAUL',
            'identification_number' =>      '74270324',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        // ------------------
        // Pinos
        // Bocamina 1
        // ------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'CELY AGUDELO',
            'name'                  =>      'JUAN AGUSTIN',
            'identification_number' =>      '4119201',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'CRUZ CORDOBA',
            'name'                  =>      'LAURENCIO',
            'identification_number' =>      '5619805',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'DAZA',
            'name'                  =>      'HELIODORO',
            'identification_number' =>      '9521039',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'GONZALEZ PALACIO',
            'name'                  =>      'PEDRO ALFONSO',
            'identification_number' =>      '7229258',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'LEON CARREÑO',
            'name'                  =>      'JESUS DAVID',
            'identification_number' =>      '13490953',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'MESA BAYONA',
            'name'                  =>      'YAMID',
            'identification_number' =>      '74180684',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'NEMPEQUE HERRERA',
            'name'                  =>      'NELSON ARLEY',
            'identification_number' =>      '74362258',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'PEREZ CARO',
            'name'                  =>      'PABLO ANTONIO',
            'identification_number' =>      '4122825',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'PEREZ NONTOA',
            'name'                  =>      'GUILLERMO',
            'identification_number' =>      '4123118',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'RODRIGUEZ LARA',
            'name'                  =>      'ROBERTO',
            'identification_number' =>      '11442310',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'SANCHEZ RIVEROS',
            'name'                  =>      'YOHN DARIO',
            'identification_number' =>      '9590374',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      8,
            'lastname'              =>      'TORRES RODRIGUEZ',
            'name'                  =>      'ORLANDO',
            'identification_number' =>      '74362219',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];


        // ------------------------
        // Sanoha
        // Bocaminina 1
        // ------------------------
        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'AGUDELO BARRERA',
            'name'                  =>      'YAMILE ASCENCION',
            'identification_number' =>      '23770519',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'AGUILAR',
            'name'                  =>      'JOSE ANTONIO',
            'identification_number' =>      '4168332',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'ARGUELLO GUAUQUE',
            'name'                  =>      'CARLOS ARTURO',
            'identification_number' =>      '74362374',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'BALLESTEROS PEREZ',
            'name'                  =>      'JUSTO PASTOR',
            'identification_number' =>      '74362078',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'BALLESTEROS PEREZ',
            'name'                  =>      'LUIS ANTONIO',
            'identification_number' =>      '4168439',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'BARRERA PARADA',
            'name'                  =>      'VICTOR JAVIER',
            'identification_number' =>      '79874470',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'CHAPARRO',
            'name'                  =>      'JOSE JAIME',
            'identification_number' =>      '17583027',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'DIAZ NEITA',
            'name'                  =>      'CARLOS ALBERTO',
            'identification_number' =>      '1054120229',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'HOLGUIN NILSON',
            'name'                  =>      'GIOVANNI',
            'identification_number' =>      '1054120659',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'HURTADO LADINO',
            'name'                  =>      'JOSE ANTONIO',
            'identification_number' =>      '74362216',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'MERCHAN',
            'name'                  =>      'GUILLERMO',
            'identification_number' =>      '4168891',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'MERCHAN GUIO',
            'name'                  =>      'VICTOR ALEJANDRO',
            'identification_number' =>      '4168802',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'MIRANDA GELVEZ',
            'name'                  =>      'OBDULIO',
            'identification_number' =>      '88162577',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'MORALES',
            'name'                  =>      'MARCO ANTONIO',
            'identification_number' =>      '80018767',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'PARADA GUAUQUE',
            'name'                  =>      'JORGE ALBERTO',
            'identification_number' =>      '4168884',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        $data[] = [
            'position_id'           =>      1,
            'sub_cost_center_id'    =>      9,
            'lastname'              =>      'SOCHA CASTRO',
            'name'                  =>      'NELSON ORLANDO',
            'identification_number' =>      '9658693',
            'created_at'            =>      $date->addMinutes($faker->numberBetween(1,10))->toDateTimeString(),
            'updated_at'            =>      $date->toDateTimeString(),
            'deleted_at'            =>      null
        ];

        DB::table('employees')->insert($data);
    }
}
