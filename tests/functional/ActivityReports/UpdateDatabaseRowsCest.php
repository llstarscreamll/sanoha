<?php
namespace ActivityReports;

use \common\BaseTest;
use \FunctionalTester;

class UpdateDatabaseRowsCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTest;
        $base_test->activityReports();

        $I->amLoggedAs($base_test->admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba el script para actualización de los datos de horas laboradas
     */
    public function update(FunctionalTester $I)
    {
        $I->am('system admin');
        $I->wantTo('probar veracidad en los cambios de datos de horas laboradas');

        $date = \Carbon\Carbon::now()->subMonth();

        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  4,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null
        ];

        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  2,
            'quantity'              =>  2,
            'price'                 =>  '6000',
            'worked_hours'          =>  8,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->copy()->addDays(1)->toDateTimeString(),
            'created_at'            =>  $date->copy()->addDays(1)->toDateTimeString(),
            'updated_at'            =>  $date->copy()->addDays(1)->toDateTimeString(),
            'deleted_at'            =>  null
        ];

        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  3,
            'quantity'              =>  2,
            'price'                 =>  '7000',
            'worked_hours'          =>  5,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->copy()->addDays(2)->toDateTimeString(),
            'created_at'            =>  $date->copy()->addDays(2)->toDateTimeString(),
            'updated_at'            =>  $date->copy()->addDays(2)->toDateTimeString(),
            'deleted_at'            =>  null
        ];

        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  4,
            'quantity'              =>  2,
            'price'                 =>  '8000',
            'worked_hours'          =>  5,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->copy()->addDays(3)->toDateTimeString(),
            'created_at'            =>  $date->copy()->addDays(3)->toDateTimeString(),
            'updated_at'            =>  $date->copy()->addDays(3)->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        // guardo los datos en la db
        \DB::table('activity_reports')->insert($data);

        // el id de la actividad
        $hoursActivity = \sanoha\Models\MiningActivity::where('short_name', 'HORAS')->first();

        // no veo las horas registradas
        $I->dontSeeRecord('activity_reports', [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  $hoursActivity->id,
            'quantity'              =>  8,
        ]);

        $I->dontSeeRecord('activity_reports', [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  $hoursActivity->id,
            'quantity'              =>  4,
        ]);

        $I->dontSeeRecord('activity_reports', [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  $hoursActivity->id,
            'quantity'              =>  5,
            'reported_at'           =>  $date->copy()->addDays(2)->toDateTimeString(),
        ]);

        $I->dontSeeRecord('activity_reports', [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  $hoursActivity->id,
            'quantity'              =>  5,
            'reported_at'           =>  $date->copy()->addDays(3)->toDateTimeString(),
        ]);

        // realizo los cambios
        $this->updateDBData();

        // ahora si debo ver los nuevos registros de horas en la base de datos
        $I->seeRecord('activity_reports', [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  $hoursActivity->id,
            'quantity'              =>  8,
        ]);

        $I->seeRecord('activity_reports', [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  $hoursActivity->id,
            'quantity'              =>  4,
        ]);

        $I->seeRecord('activity_reports', [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  $hoursActivity->id,
            'quantity'              =>  5,
            'reported_at'           =>  $date->copy()->addDays(2)->toDateTimeString(),
        ]);

        $I->seeRecord('activity_reports', [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  $hoursActivity->id,
            'quantity'              =>  5,
            'reported_at'           =>  $date->copy()->addDays(3)->toDateTimeString(),
        ]);
    }

    /**
     * Realiza el proceso de actualización de los datos
     */
    private function updateDBData()
    {
        // inicio una database trnasaction, para acelerar el proceso
        //\DB::beginTransaction();

        $date = \Carbon\Carbon::now()->subMonth();

        $data[] = [
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  1,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  4,
            'comment'               =>  'test',
            'reported_by'           =>  1,
            'reported_at'           =>  $date->toDateTimeString(),
            'created_at'            =>  $date->toDateTimeString(),
            'updated_at'            =>  $date->toDateTimeString(),
            'deleted_at'            =>  null
        ];
        
        \DB::table('activity_reports')->insert($data);

        // instancia del modelo
        $reports = new \sanoha\Models\ActivityReport;
        $hoursActivity = \sanoha\Models\MiningActivity::where('short_name', 'HORAS')->first();

        // obtengo la fecha más antigua y reciente para el bucle
        $start = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \sanoha\Models\ActivityReport::min('reported_at'));
        $end = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \sanoha\Models\ActivityReport::max('reported_at'));
        $data = []; // array de datos a insertar
        $rows = 0; // filas creadas

        // recorro el rango de fechas
        while ($start->toDateString() <= $end->toDateString()) {
            
            // obtengo las horas laboradas de cada trabajador en X dia
            $result = \DB::select('select max(worked_hours) as hours,
                employee_id,
                sub_cost_center_id,
                reported_at,
                reported_by,
                created_at,
                updated_at
                from activity_reports
                where reported_at between ? and ?
                group by employee_id
                order by employee_id;', [$start->startOfDay()->toDateTimeString(), $start->endOfDay()->toDateTimeString()]
            );

            // armo el array según los resultados que devuleva la consulta
            if(count($result) > 0){
                
                foreach ($result as $key => $value) {

                    $data = [
                    'id'                    =>  null,
                    'sub_cost_center_id'    =>  $value->sub_cost_center_id,
                    'employee_id'           =>  $value->employee_id,
                    'mining_activity_id'    =>  $hoursActivity->id,
                    'quantity'              =>  $value->hours,
                    'price'                 =>  0,
                    'worked_hours'          =>  0,
                    'comment'               =>  'Registro creado automáticamente por el sistema como actualización a la forma en que se almacenan y cuantifican las horas laboradas por los trabajadores',
                    'reported_by'           =>  $value->reported_by,
                    'reported_at'           =>  $value->reported_at,
                    'created_at'            =>  $value->created_at,
                    'updated_at'            =>  $value->updated_at,
                    'deleted_at'            =>  null,
                    ];

                    // trato de guardar el registro
                    if (\DB::table('activity_reports')->insert($data)){
                        $rows++;
                    }else{
                        $rows = 0;
                        //\DB::rollback();
                        exit;
                    }

                }

            }
            
            // añado un día a la fecha
            $start->addDay();

        }

        // si hay por lo menos una fila,
        //count($rows) > 0 ? \DB::commit() : '';
    }
}