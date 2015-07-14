<?php namespace ActivityReports;

use \FunctionalTester;
use \Carbon\Carbon;
use \Users\_common\UserCommons;
use \ActivityReports\_common\ActivityReportsCommons;

class FilterActivityReportCest
{
    public function _before(FunctionalTester $I)
    {
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();
        $this->userCommons->haveUsers(10); // creo 10 usuarios
        $this->userCommons->haveEmployees(10); // crea 10 empleados + 2 por defecto
        $this->userCommons->haveMiningActivities();
        
        $this->activityReportsCommons = new ActivityReportsCommons;

        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Probar el reporte generado por defecto
     * 
     * @param
     */ 
    public function testDefaultReport(FunctionalTester $I)
    {
        // deferencia en días desde hoy para crear el reporte y los datos de prueba
        $days = 1; // uno porque el reporte que se genera por defecto es del día anterior
        $project_id = 2; // centro de costos o Proyecto Beteitiva, se creó en SystemCommons
        
        // inicio el test
        $I->am('supervisor del Projecto Beteitiva');
        $I->wantTo('ver que actividades se han reportado el día de ayer');
        
        $this->generateDynamicReportTest($days, $project_id, $I);
    }
    
    /**
     * Probar el reporte generado dinámicamente según el rango de fechas reuqerido
     * fijado por la variable $days, para llegar aquí se debe haber hecho algunos
     * pasos antes, como por ejemplo elegir el rango de fechas en el frontend,
     * texto, etc...
     * 
     * @param $days int El número de dás a restar para obtener la fecha de inicio del reporte
     */
    private function generateDynamicReportTest($days, $project_id, $I)
    {
        // los datos del proyecto o centro de costos en cuestión
        $project = \sanoha\Models\CostCenter::find($project_id);
        
        // creo reportes ficticios para los días requeridos
        $this->activityReportsCommons->haveActivityReports(date('Y-m-d'), $days+1);
        
        // rango de fechas por defecto en la que se hará el reporte
        $start = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 00:01:00');
        $end = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 23:59:00');
        
        // dias anteriores
        $start->subDays($days);
        $end->subDays($days);
        
        // los parametros para el filtro
        $parameters = [
            'from'          =>  $start,
            'to'            =>  $end,
            'costCenter_id' =>  $project_id // el id del centro de costos Beteitiva
        ];
        
        // datos de la actividad, el index debe mostrar los reportes del día pasado
        $report = \sanoha\Models\ActivityReport::sortActivities(\sanoha\Models\ActivityReport::getActivities($parameters));
        
        // empeiza el test
        $I->amOnPage('/home');
        $I->click($project->name, 'a'); // el proyecto o centro de costo creado en UserCommons
        
        // hago clic en el vinculo al proyecto que tengo acceso
        $I->seeCurrentUrlEquals('/activityReport');
        $I->seeInSession('currentCostCenterActivities', $project->id); // el id del centro de costos ue seleccioné
        
        // titulo de la página
        $I->see('Reporte de Actividades');
        
        // no veo el mensaje "No se encontraron registros..."
        $I->dontSee('No se encontraron registros...', '.alert-danger');
        
        // veo la tabla donde se muestran los datos
        $I->see('', 'table');
        
        // veo que tipo de reporte y de que proyecto es el reporte
        $I->see($project->name, 'th h3');
        
        // el rango de fechas del reporte debe ser mostrado en la tabla
        $I->see('Desde '.$start->format('d-m-Y'), 'th h4');
        $I->see('Hasta '.$end->format('d-m-Y'), 'th h4');
        
        // veo links a los detalles de los usuarios con el nombre completo que
        // han reportado las actividades
        foreach ($report['reported_by'] as $rprt_by) {
            $I->see($rprt_by, 'a');
        }
        
        //veo que el nombre corto de todas las actividades mineras están en
        // la cabecera de la tabla, pero tienen su nombre completo en el atributo title
        $miningActivities = \sanoha\Models\MiningActivity::orderBy('name')->get();
        //dd($miningActivities);
        foreach ($miningActivities as $activity) {
            $I->see($activity->short_name, 'th');
            $I->seeElement('th span', ['title' => $activity->name, 'data-toggle' => 'tooltip']);
        }
        
        // veo las actividades en cuestión, que para esta prueba son dos nada mas,
        // las actividades han sido generadas en
        // ActivityReportsCommons::haveActivityReports($days = 90), se han generado
        // dos por cada día, así:
        //
        //  1a actividad
        //  $data[] = [
        //         'employee_id'           =>      1,
        //         'mining_activity_id'    =>      1,
        //         'quantity'              =>      2,
        //         'price'                 =>      2000,
        //         'reported_by'           =>      $faker->numberBetween(1,10),
        //         'created_at'            =>      $date->addMinutes($faker->numberBetween(1,2))->toDateTimeString(),
        //         'updated_at'            =>      $date->toDateTimeString(),
        //         'deleted_at'            =>      null
        //     ];
        //
        // entonces debo ver el nombre de dichos empleados
        foreach ($report as $rep) {
            if(isset($rep['employee_fullname']))
                $I->see($rep['employee_fullname'], 'td');
        }
        
        // debo ver la actividad que realizó cada empleado
        $row = 1; // la fila del registro
        foreach ($report as $activityKey => $activity) {
            
            $column = 2; // la columna donde inician a verse las actividades mineras, 1 es para el nombre
            
            foreach ($miningActivities as $miningActivityKey => $miningActivity) {
            
                if($activityKey !== 'totals' && $activityKey!== 'reported_by'){
                    // veo el nombre del empleado
                    $I->see($activity['employee_fullname'], 'tbody tr:nth-child('.$row.') td:nth-child(1)');
                    
                    // y en la misma fila donde vi al empleado, veo sus actividaes registradas
                    $I->see($miningActivity->short_name, 'thead th');
                    $I->see($activity[$miningActivity->short_name], 'tbody tr:nth-child('.$row.') td:nth-child('.$column.')');
                }    
                if($activityKey === 'totals'){
                    // veo la suma total de cada actividad
                    $I->see('Total', 'tbody tr:last-child td:first-child');
                    $I->see($miningActivity->short_name, 'thead th');
                    $I->see($activity[$miningActivity->short_name], 'tbody tr:last-child td:nth-child('.$column.')');
                }
                    
                $column++;
                
            }
            
            $row++;
        }
    }
}