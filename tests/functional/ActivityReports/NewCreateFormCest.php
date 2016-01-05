<?php
namespace ActivityReports;

use \FunctionalTester;
use \common\BaseTest;

class NewCreateFormCest
{
    public function _before(FunctionalTester $I)
    {
        $this->base_test = new BaseTest;
        $this->base_test->activityReports();

        $I->amLoggedAs($this->base_test->admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba que no se pueda asignar "de forma brusca" el precio de una actividad minera
     */
    public function forcePriceAssign(FunctionalTester $I)
    {
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('probar que no puedo forzar la asignacion de precios de labores...');

        // quito el permiso de asignar costos, pues el supervisor no debe hacerlo
        $permissions = \sanoha\Models\Permission::where('name', '!=', 'activityReport.assignCosts')->get()->lists('id')->all(); // obtengo el permiso que quiero quitar
        $admin_role = \sanoha\Models\Role::where('name', '=', 'admin')->first();
        $admin_role->perms()->sync($permissions);

        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');

        // la página de creación reporte de actividades mineras con el Trabajador 1 seleccionado
        $I->amOnPage('/activityReport/new_activity_report_form?employee_id=2');

        // envío el formulario
        $I->submitForm('form', [
            'employee_id'               =>  2,
            'mining_activity'           =>  [1 => 2],
            'mining_activity_price'     =>  [1 => 1200],
            'reported_at'               =>  '2015-12-14',
            'comment'                   =>  'Tratando de asignar un precio sin tener permisos'
        ]);

        // soy redirigido a la página del nuevo cargador de actividades mineras
        $I->seeCurrentUrlEquals('/activityReport/new_activity_report_form');
        // veo mensage de exito en la operación
        $I->see('Actividades reportadas correctamente.', '.alert-success');
        
        // veo los registros en la base de datos
        $I->seeRecord('activity_reports', [
            'sub_cost_center_id'=>  2,
            'employee_id'       =>  2,
            'mining_activity_id'=>  1,
            'quantity'          =>  2,
            // el precio debe quedar en 0 aún si se modificó el DOM y se digitó un precio (12000),
            // porque se verifíca si tiene o no permiso, no si hay un valor o no...
            'price'             =>  0,
            'worked_hours'      =>  0,
            'comment'           =>  'Tratando de asignar un precio sin tener permisos',
            'reported_by'       =>  1
        ]);
    }

    /**
     * Prueba que si no se tienen permisos para asignar precios, las cajas de precio de
     * actividades están deshabilidatas, o de sólo lectura
     */
    public function pricePermissions(FunctionalTester $I)
    {
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('probar que no puedo asignar precios de labores');

        // quito el permiso de asignar costos, pues el supervisor no debe hacerlo
        $permissions = \sanoha\Models\Permission::where('name', '!=', 'activityReport.assignCosts')->get()->lists('id')->all(); // obtengo el permiso que quiero quitar
        $admin_role = \sanoha\Models\Role::where('name', '=', 'admin')->first();
        $admin_role->perms()->sync($permissions);

        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');

        // la página de creación reporte de actividades mineras con el Trabajador 1 seleccionado
        $I->amOnPage('/activityReport/new_activity_report_form?employee_id=1');

        // veo los campor de precio en modo sólo lectura
        foreach (\sanoha\Models\MiningActivity::all() as $activity) {

            $I->seeElement('input', [
                'type'  =>  'number',
                'name'  =>  'mining_activity_price['.$activity->id.']',
                'value' =>  '',
                'step'  => '50',
                'readonly'  =>  'readonly',
                'placeholder' => 'Precio'
            ]);
        }
    }

    /**
     * Prueba la funcionalidad del nuevo cargador de actividades mineras
     */
    public function newReportForm(FunctionalTester $I)
    {
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('probar el nuevo formulario de reporte de actividades');

        // creo dos registros antiguos para que tenga referencia para asígnar el precio
        // de la actividad que voy a registrar, los parametros para resolver el precio
        // de la actividad son la bocamina (subcentro de costo) y el empleado...
        \sanoha\Models\ActivityReport::create([
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  2,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  4,
            'comment'               =>  'test comment',
            'reported_by'           =>  1,
            'reported_at'           =>  '2015-01-01 01:01:01'
        ]);
        
        \sanoha\Models\ActivityReport::create([
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  2,
            'mining_activity_id'    =>  2,
            'quantity'              =>  4,
            'price'                 =>  '7000',
            'worked_hours'          =>  4,
            'comment'               =>  'comentario para actividad',
            'reported_by'           =>  1,
            'reported_at'           =>  '2015-01-02 01:01:01'
        ]);

        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        // doy clic al botón de registrar una actividad minera
        $I->click('Registrar Labor Minera', 'a');
        // veo que estoy en la url indicada
        $I->seeCurrentUrlEquals('/activityReport/create');

        // doy clic al respectivo link para acceder al nuevo cargador
        //$I->click('Usar Nuevo Formulario de Reporte de Actividades', '.well a');

        // la página de creación reporte de actividades mineras con el Trabajador 1 seleccionado
        $I->amOnPage('/activityReport/new_activity_report_form');

        // veo que estoy en la página del nuevo cargador
        $I->seeCurrentUrlEquals('/activityReport/new_activity_report_form');
        // titulo de la página
        $I->see('Reporte de Labores Mineras');
        // no veo los campos para diligenciar las actividades
        $I->dontSeeElement('input', ['name' => 'mining_activity[1]']);
        $I->dontSeeElement('input', ['name' => 'mining_activity_price[1]']);
        // veo un mensaje diciendo que debo seleccionar un empleado
        $I->see('Selecciona un trabajador...', '.alert-warning');

        // debo seleccionar al trabajador para poder diligenciar los campos
        $I->submitForm('form', ['employee_id' => 2]);

        // veo que soy redirigido a la página con el nuevo cargador de actividades
        $I->seeCurrentUrlEquals('/activityReport/new_activity_report_form?employee_id=2');
        // veo que el empleado que elegí esta seleccionado en el select
        $I->seeInformFields('form', ['employee_id' => 2]);
        // veo el campo informativo de asistencia
        $I->seeElement('input', ['name' => 'attended', 'type' => 'checkbox']);

        // ahora debo ver los campos para reportar las actividades
        foreach (\sanoha\Models\MiningActivity::all() as $activity) {

            // veo un input de tipo numérico donde digitar la cantidad de cada actividad minera
            $I->seeElement('input', [
                'type'  =>  'number',
                'name'  =>  'mining_activity['.$activity->id.']',
                'max'   =>  $activity->maximum,
                'min'   =>  '0'
            ]);

            // veo un input de tipo numérico donde digito el precio de cada actividad minera, esos
            // campos aparecen si se tienen los permisos para asignar precios
            if($activity->id == 2){
                $I->seeElement('input', [
                    'type'  =>  'number',
                    'name'  =>  'mining_activity_price['.$activity->id.']',
                    'value' =>  '7000',
                    'step'  => '50',
                    'placeholder' => 'Precio'
                ]);
            }else{
                $I->seeElement('input', [
                    'type'  =>  'number',
                    'name'  =>  'mining_activity_price['.$activity->id.']',
                    'value' =>  '',
                    'step'  => '50',
                    'placeholder' => 'Precio'
                ]);
            }
        }

        // veo el campo de fecha
        $I->seeElement('input', ['name' => 'reported_at']);
        $I->seeElement('textarea', ['name' => 'comment']);

        // envío el formulario
        $I->submitForm('form', [
            'employee_id'               =>  2,
            'mining_activity'           =>  [1 => 2, 2 => 3],
            'mining_activity_price'     =>  [1 => 12000, 2 => 6000],
            'reported_at'               =>  '2015-12-14',
            'comment'                   =>  'Prueba de comentario'
        ]);

        // soy redirigido a la página del nuevo cargador de actividades mineras
        $I->seeCurrentUrlEquals('/activityReport/new_activity_report_form');
        // veo mensage de exito en la operación
        $I->see('Actividades reportadas correctamente.', '.alert-success');
        
        // veo los registros en la base de datos
        $I->seeRecord('activity_reports', [
            'sub_cost_center_id'=>  2,
            'employee_id'       =>  2,
            'mining_activity_id'=>  1,
            'quantity'          =>  2,
            'price'             =>  12000,
            'worked_hours'      =>  0,
            'comment'           =>  'Prueba de comentario',
            'reported_by'       =>  1
        ]);

        $I->seeRecord('activity_reports', [
            'sub_cost_center_id'=>  2,
            'employee_id'       =>  2,
            'mining_activity_id'=>  2,
            'quantity'          =>  3,
            'price'             =>  6000,
            'worked_hours'      =>  0,
            'comment'           =>  'Prueba de comentario',
            'reported_by'       =>  1
        ]);

        // no debo ver registros con valores en cero
        $I->dontSeeRecord('activity_reports', [
            'sub_cost_center_id'=>  2,
            'employee_id'       =>  2,
            'mining_activity_id'=>  3
        ]);

        $I->dontSeeRecord('activity_reports', [
            'sub_cost_center_id'=>  2,
            'employee_id'       =>  2,
            'mining_activity_id'=>  4
        ]);
    }
}