<?php namespace ActivityReports;

use \FunctionalTester;
use \common\BaseTest;

class ReportCest
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
     * Pruebo los mensajes de error al reportar una novedad
     */
    public function testErrorMessages(FunctionalTester $I)
    {
        // info del trabajador
        $employee = \sanoha\Models\Employee::first();
        $date = \Carbon\Carbon::now();

       // soy un supervisor minero y quiero probar los mensajes de error o alerta
       // cuando intente ingresar información inválida en el formuario
       $I->am('supervisor minero');
       $I->wantTo('probar los mensajes de error del formulario de reporte de actividades mineras');
       
        // estoy en el home
        $I->amOnPage('/home');
        
        // hago clic en el proyecto de trabajo y luego en registar labor mienra
        $I->click('Proyecto Beteitiva', 'a');
        $I->click('Registrar Labor Minera', 'a');
           
        // estoy en la página de registro de la actividad
        $I->seeCurrentUrlEquals('/activityReport/create');
       
        // veo que el título de la página es el que corresponde
        $I->see('Reportar Labor Minera', 'fieldset');
       
        // selecciono al trabajador y envío el formulario para que me cargue los demás campos
        $I->submitForm('form', ['employee_id' => 1]);
        
        // veo que la url cambia un poco, pues tiene el id del empleado seleccionado
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
        
        // ----------------------------
        // ----- primera prueba -------
        // ----------------------------
        // aquí los datos erroneos con lo que voy a enviar el formulrio
        $report = [
            'employee_id'           =>  876, // envío el formulario con la info de un empleado que no existe
            'mining_activity_id'    =>  2649, // esta actividad tampoco existe
            'quantity'              =>  600, // la cantidad sobrepasa lo permitido por la actividad minera seleccionada
            'worked_hours'          =>  58, // horas trabajadas
            'reported_at'           =>  '2015-5595', // horas trabajadas
            'comment'               =>  'Test coment with dots...' // los comentarios no deben tener puntos
        ];
       
        // envío el formulario
        $I->submitForm('form', $report, 'Registrar');
       
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
       
        // debo ver el título correspondiente
        $I->see('Reportar Labor Minera', 'legend');
       
        // veo que el formulario tiene unos errores
        $I->seeFormHasErrors();
       
        // veo que cada error error es mostrado en una capa con el correspondiente
        // estilo de error o alerta
        $I->see('Trabajador inválido.', '.text-danger');
        $I->see('Labor minera inválida.', '.text-danger');
        $I->see('El tiempo trabajado debe ser entre 1 y 12 horas.', '.text-danger');
        $I->see('El comentario sólo debe contener letras y/o espacios.', '.text-danger');
        $I->see('La fecha tiene un formato inválido.', '.text-danger');

        // no veo error en la cantidad pues no tiene referencia válida a la labor minera para obtener el límite
        $I->dontSee('Debes digitar la cantidad.', '.text-danger');
        
        // ----------------------------
        // ----- segunda prueba -------
        // ----------------------------
        
        $I->wantTo('probar los mensajes de error en identifidores de trabajador y labor minera');
        
        // aquí los datos erroneos con lo que voy a enviar el formulrio
        $report = [
            'employee_id'           =>  'mm', // empleado con formato de id inválido
            'mining_activity_id'    =>  'nn', // actividad con formato erróneo
            'quantity'              =>  '', // la cantidad es obligatoria
            'reported_at'           =>  $date->toDateString(),
            'comment'               =>  '',
        ];
       
        // envío el formulario
        $I->submitForm('form', $report, 'Registrar');
       
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
       
        // debo ver el título correspondiente
        $I->see('Reportar Labor Minera', 'legend');
       
        // veo que el formulario tiene unos errores
        $I->seeFormHasErrors();
       
        // veo que cada error error es mostrado en una capa con el correspondiente
        // estilo de error o alerta
        $I->see('Identificador de empleado inválido.', '.text-danger');
        $I->see('Identificador de labor minera inválido.', '.text-danger');
        $I->dontSee('Debes digitar la cantidad.', '.text-danger');
        // ya no veo mensaje de error del comentario
        $I->dontSee('El comentario sólo debe contener letras y/o espacios.', '.text-danger');
        
        
        // ----------------------------
        // ------ tercera prueba ------
        // ----------------------------
        
        $I->wantTo('probar mensajes de error en la cantidad de la labor minera');
        
        // aquí los datos erroneos con lo que voy a enviar el formulrio
        $report = [
            'employee_id'           =>  1, // envío el formulario con la info de un empleado que no existe
            'mining_activity_id'    =>  1, // esta actividad tampoco existe
            'quantity'              =>  600, // la cantidad sobrepasa lo permitido por la actividad minera seleccionada
            'reported_at'           =>  '',
            'comment'               =>  'Comentario de prueba' // los comentarios no deben tener puntos
        ];

        // envío el formulario
        $I->submitForm('form', $report, 'Registrar');
       
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
       
        // debo ver el título correspondiente
        $I->see('Reportar Labor Minera', 'legend');
        
        $I->see('Selecciona la fecha en que fue realizada la actividad.', '.text-danger');
        // ahora si veo error en la cantidad pues tiene referencia válida a la labor minera para obtener el límite
        $I->see('El rango permitido es entre 0.1 y 100.', '.text-danger');
    }
    
    /**
     * Pruebo la restricción de no reportar la misma actividad minera dos veces en el mismo día
     */
    public function repeatMiningAticity(FunctionalTester $I)
    {
        $date = \Carbon\Carbon::now()->toDateString();
        
        // la actividad a duplicar
        \sanoha\Models\ActivityReport::create([
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  2,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  4,
            'comment'               =>  'test comment',
            'reported_by'           =>  1,
            'reported_at'           =>  $date
        ]);
        
        $I->am('un supervisor');
        $I->wantTo('tratar de reportar la misma actividad dos veces en el mismo dia');
        
        // ya inicié sesión como un usuario con todos los privilejios
        $I->seeAuthentication();
        
        // estoy en el home
        $I->amOnPage('/home');
        
        // selecciono el centro de costos con el que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        // pulso el botón para reportar una nueva actividad
        $I->click('Registrar Labor Minera', 'a');
        
        // selecciono un trabajador para ver los demás campos
        $I->submitForm('form', ['employee_id' => 1]);
        
        $I->submitForm('form', [
            'employee_id'           =>  1,
            'mining_activity_id'    =>  2,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  4,
            'reported_at'           =>  $date,
            'comment'               =>  'otro comenatrio de prueba'
        ], 'Registrar');
        
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
        $I->dontSeeElement('div', ['class' => 'alert alert-success alert-dismissible']);
        $I->see('El trabajador ya reportó Vagoneta de Roca el día '.$date.'.', '.alert-danger');
    }

    /**
     *  Pruebo el formulario de reporte de actividad o labor minera
     * 
     * @param FunctionalTester $I
     */
    public function reportMiningActivity(FunctionalTester $I)
    {
        // id del projecto con el que voy a trabajar
        $project_id = 1; // Beteitiva
        
        // quito el permiso de asignar costos, pues el supervisor no debe hacerlo
        $permissions = \sanoha\Models\Permission::where('name', '!=', 'activityReport.assignCosts')->get()->lists('id'); // obtengo el permiso que quiero quitar
        $admin_role = \sanoha\Models\Role::where('name', '=', 'admin')->first();
        $admin_role->perms()->sync($permissions);
        
        // necesito la lista de labores mineras que puedo registrar
        $labors = \sanoha\Models\MiningActivity::all();
        $date = \Carbon\Carbon::now();
        
        // creo dos registros antiguos para que tenga referencia para asígnar el precio
        // de la actividad que voy a registrar, los parametros para resolver el precio
        // de la actividad son la bocamina y el empleado.
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
        
        // -----------------------
        // --- Empieza el test ---
        // -----------------------
        
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('registrar la actividad minera de un trabajador');
        
        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        // doy clic al botón de registrar una actividad minera
        $I->click('Registrar Labor Minera', 'a');
        
        // veo que estoy en la url indicada
        $I->seeCurrentUrlEquals('/activityReport/create');
        
        // veo que está divido en secciones el formulario, un fielset donde
        // están los compos para el registro y otro fieldset donde está la vista previa de
        // los datos cargados al trabajador
        $I->see('Reportar Labor Minera', 'fieldset legend');
        $I->see('Vista Previa de Actividades', 'fieldset legend');
        
        // veo que hay un select con los nombres de los trabajadores del centro
        // de costos que seleccioné
        $I->see('Trabajador 1 B1', 'select optgroup option');
        $I->see('Trabajador 2 B2', 'select optgroup option');
        
        // veo que no están presentes muchos campos porque debo elegir primero al trabajador
        $I->dontSeeElement('input', ['type' => 'checkbox', 'checked' => 'checked']); // por defecto está marcado
        $I->dontSeeElement('select', ['name' => 'mining_activity_id']);
        $I->dontSeeElement('input', ['name' => 'quantity']);
        $I->dontSeeElement('input', ['name' => 'price']);
        $I->dontSeeElement('input', ['name' => 'reported_at']);
        $I->dontSeeElement('input', ['name' => 'worked_hours']);
        $I->dontSeeElement('textarea', ['name' => 'comment']);
        $I->dontSeeElement('button', ['type' => 'submit']);
        
        // veo que en la vista preliminar tengo un mensaje que dice "Selecciona un trabajador"
        // pues no he seleccionado alguno para ver los datos de las labores mineras que se le
        // han cargado del día en curso
        $I->see('Selecciona un trabajador...', '.alert-warning');
        
        // veo que el atributo action del formulario es /localhost/activityReport/create,
        // para que en la siguiente carga pueda pueda cargar la vista previa de los datos
        // del empleado
        $I->seeElement('form', ['method'    =>  'GET']);
        
        // selecciono un trabajador de la lista y hago la simulación de envío del formulario
        // aunque no tengo botón, esto se hará con javascript en el onChange del select
        $I->submitForm('form', ['employee_id' => 1]);
        
        // la página se recarga al elejir al trabajador, veo que estoy de nuevo en
        // la misma página pero con el parámetro del trabajador seleccionado
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
        
        // veo que el select tiene ya cargado el empleado seleccionado anteriormente
        $I->seeOptionIsSelected('#employee_id', 'Trabajador 1 B1');
        
        // ahora si veo los campos faltantes del formulario para poder registrar la actividad
        $I->seeElement('input', ['type' => 'checkbox', 'name' =>  'attended', 'checked' => 'checked']);
        $I->seeElement('select', ['name' => 'mining_activity_id']); // el select con las labores mineras
        $I->seeElement('input', ['name' =>  'quantity']); // el input para digitar la cantidad
        $I->seeElement('input', ['name' =>  'worked_hours']); // el input para digitar la cantidad
        $I->seeElement('input', ['name' =>  'reported_at']); // el input para digitar la fecha en que se hizo la actividad
        // ------------------------------------------------------------------------------------------
        // Nuevo Requerimiento...
        //
        // el supervisor no puede asignar precios de las labores mineras registradas, por lo tanto
        // no puede ver el campo price o precio en el formulario, esto queda para un proceso aparte,
        // pero el precio jamás debe quedar vació, se debe asignar automáticamente en el backend según
        // los históricos de x actividad.
        // ------------------------------------------------------------------------------------------
        $I->dontSeeElement('input', ['name' => 'price']); // NO VEO el input para digitar el precio
        $I->seeElement('input', ['name' => 'worked_hours', 'step' => '1', 'max' => '12']); // campo de horas trabajadas, máximo 12 horas a reportar
        $I->seeElement('button', ['type' => 'submit']); // el botton para enviar el formulario
        
        // ahora si veo la tabla donde se mostrarán los registros de las actividades del trabajador
        $I->seeElement('table', ['class' => 'table table-hover table-bordered table-vertical-align-middle']);
        
        //veo que el nombre corto de todas las actividades mineras están en
        // la cabecera de la tabla, pero tienen su nombre completo en el atributo title
        foreach ($labors as $activity) {
            $I->see($activity->short_name, 'th');
            $I->seeElement('th span', ['title' => $activity->name, 'data-toggle' => 'tooltip']);
        }
        
        // el nombre del trabajador no debe aparecer en la tabla, pues no reporta actividades
        $I->dontSee('Trabajador 1 B1', 'tbody tr:first-child td:first-child');
        
        // veo que hay un mensaje de alerta que me dice que nada se le ha cargado al trabajador
        $I->see('No hay actividades registradas...', 'div.alert-warning');
        
        // lleno y envío el fomulario registrando una nueva actividad del trabajador
        $activityToReport = [
            'employee_id'           =>  1,
            'mining_activity_id'    =>  2,
            'quantity'              =>  2.5,
            'worked_hours'          =>  8,
            'reported_at'           =>  $date->copy()->toDateTimeString(),
            'comment'               =>  'Comentario de prueba'
        ];
        
        $I->dontSeeRecord('activity_reports', $activityToReport);
        
        $activityToReport['reported_at'] = $date->copy()->toDateString();
        $I->submitForm('form', $activityToReport, 'Registrar');
        
        // veo que estoy de nuevo en la página de registro de actividad minera
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
        
        // no debe haber mensajes de alerta o error
        $I->dontSeeElement('div', ['class' => 'alert alert-warning alert-dismissible']);
        $I->dontSeeElement('div', ['class' => 'alert alert-danger alert-dismissible']);
        
        // veo un mensaje de éxito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');

        // refresco la página
        $I->amOnPage('/activityReport/create?employee_id=1');
        
        // veo que en la tabla de la vista previa está el registro que acabo de cargar, con el precio histórico que se ha asignado en ese centro de costo a esa actividad
        $I->see('2.5', 'tbody tr td');
        $I->dontSee('17.500', 'tbody tr td'); // no veo 17.500 porque los 7 mil se le han pagado a otro minero, 2.5 * 7000 = 17.500
        $I->see('12.500', 'tbody tr td'); // 2.5 a $5.000 cada actividad que fue lo que se ha pagado antes a "este empelado"
    }
    
    /**
     * Pruebo que determinado usuario puede asignar costos y otro no dependiendo de
     * los permisos que tenga asignados...
     */
    public function assignCosts(FunctionalTester $I)
    {
        // ------------------------------
        // ---- usuario CON permisos ----
        // ------------------------------
        $I->am('un usuario administrador con permiso para asignar costos');
        $I->wantTo('reportar una actividad y asignar el costo de la misma');
        
        // ya inicié sesión como un usuario con todos los privilejios
        $I->seeAuthentication();
        
        // estoy en el home
        $I->amOnPage('/home');
        
        // selecciono el centro de costos con el que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        // pulso el botón para reportar una nueva actividad
        $I->click('Registrar Labor Minera', 'a');
        
        // selecciono un trabajador para ver los demás campos
        $I->submitForm('form', ['employee_id' => 1]);
        
        // y veo que todos los campos están presentes, incluido el de asignar
        // costo a la actividad pues yo tengo asignado tal permiso
        $I->seeElement('select', ['name'  =>  'employee_id']);
        $I->seeElement('select', ['name'  =>  'mining_activity_id']);
        $I->seeElement('input', ['name'  =>  'quantity']);
        $I->seeElement('input', ['name'  =>  'price']);
        $I->seeElement('textarea', ['name'  =>  'comment']);
        
        // la info del reporte a enviar
        $date = \Carbon\Carbon::now();
        $data = [
            'employee_id'           =>      1,
            'mining_activity_id'    =>      2,
            'price'                 =>      15000,
            'quantity'              =>      4,
            'worked_hours'          =>      8,
            'reported_at'           =>      $date->toDateString(),
            'comment'               =>      'test comment'
        ];
        
        // envío el formluario
        $I->submitForm('form', $data);
        
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');

        // veo un mensaje de exito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');
        
        // veo que en la tabla de la vista previa está el registro que acabo de cargar
        //dd(\sanoha\Models\ActivityReport::all()->toArray());
        $I->see('4', 'tbody tr td');
        $I->see('60.000', 'tbody tr td');
        
        // cierro sesión
        $I->logout();
        
        // ------------------------------
        // ---- usuario SIN permisos ----
        // ------------------------------
        
        // quito el permiso de asignar costos, pues el supervisor no debe hacerlo
        $permissions = \sanoha\Models\Permission::where('name', '!=', 'activityReport.assignCosts')->get()->lists('id'); // obtengo el permiso que quiero quitar
        $admin_role = \sanoha\Models\Role::where('name', '=', 'admin')->first();
        $admin_role->perms()->sync($permissions);
        
        // inicio sesión con un usuario que no puede asignar costos
        $I->amLoggedAs($this->base_test->admin_user); // ya se lequitaron los permisos
        
        // estoy en el home
        $I->amOnPage('/home');
        
        // selecciono el centro de costos con el que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        // pulso el botón para reportar una nueva actividad
        $I->click('Registrar Labor Minera', 'a');
        
        // selecciono un trabajador para ver los demás campos
        $I->submitForm('form', ['employee_id' => 1]);
        
        // y veo que todos los campos están presentes, menos el de asignar
        // costo a la actividad pues yo no tengo asignado tal permiso
        $I->dontSeeElement('input', ['name'  =>  'price']);
        
        // la info del reporte a enviar
        $data = [
            'employee_id'           =>      2,
            'mining_activity_id'    =>      4,
            'quantity'              =>      1,
            'worked_hours'          =>      5,
            'reported_at'           =>      $date->toDateString(),
            //'price'               =>      15000, // no puedo asignar este campo
        ];
        
        // envío el formluario
        $I->submitForm('form', $data);
        
        unset($data['reported_at']);
        
        //dd(\sanoha\Models\ActivityReport::all()->toArray());
        // veo en la base de datos el nuevo registro
        $I->seeRecord('activity_reports', $data+['price' => 0]);
        
        // como no se asignó precio por el usuario y porque no hay histórico, entonces
        // debo ver un mensage de alerta donde me informe que no se ha asignado el precio
        // automáticamente porque no hay históricos en que basar la selección del precio
        $I->see('La actividad fue registrada, pero no se asignó el precio porque no hay históricos en que basar la selección.', '.alert-warning');
        
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
        
        // veo un mensaje de exito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');
        
        // veo que en la tabla de la vista previa está el registro que acabo de cargar
        $I->see('1', 'tbody tr td');
        $I->see('0', 'tbody tr td');
    }
    
    /**
     * Pruebo la seguridad al asignar los precios de las actividades mineras cuando
     * un usuario no tiene permisos para hacerlo
     */
    public function forceAssignCost(FunctionalTester $I)
    {
        // quito los permisos para asignar costos
        $permissions = \sanoha\Models\Permission::where('name', '!=', 'activityReport.assignCosts')->get()->lists('id'); // obtengo el permiso que quiero quitar
        $admin_role = \sanoha\Models\Role::where('name', '=', 'admin')->first();
        $admin_role->perms()->sync($permissions);
        
        $I->am('un usuario sin permiso para asignar costos a labores mineras');
        $I->wantTo('reportar actividad y tratar de asignar el costo de la misma');
        
        // ya inicié sesión como un usuario con todos los privilejios
        $I->seeAuthentication();
        
        // estoy en el home
        $I->amOnPage('/home');

        // selecciono el centro de costos con el que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        // pulso el botón para reportar una nueva actividad
        $I->click('Registrar Labor Minera', 'a');
        
        // selecciono un trabajador para ver los demás campos
        $I->submitForm('form', ['employee_id' => 1]);
        
        // y veo que todos los campos están presentes, menos el de asignar
        // costo a la actividad pues yo no tengo asignado tal permiso
        $I->dontSeeElement('input', ['name'  =>  'price']);
        
        // la info del reporte a enviar
        $date = \Carbon\Carbon::now();
        $data = [
            'employee_id'           =>      1,
            'mining_activity_id'    =>      4,
            'quantity'              =>      1,
            'worked_hours'          =>      5,
            'reported_at'           =>      $date->toDateString(),
            'price'                 =>      15000, // no veo el input pero de todos modos envío el costo
        ];
        
        // envío el formluario
        $I->submitForm('form', $data);
        
        unset($data['reported_at']);
        unset($data['price']);
        
        //dd(\sanoha\Models\ActivityReport::all()->toArray());
        // veo en la base de datos el nuevo registro
        $I->seeRecord('activity_reports', $data+['price' => 0]);
        
        // como no se asignó precio por el usuario y porque no hay histórico, entonces
        // debo ver un mensage de alerta donde me informe que no se ha asignado el precio
        // automáticamente porque no hay históricos en que basar la selección del precio
        $I->see('La actividad fue registrada, pero no se asignó el precio porque no hay históricos en que basar la selección.', '.alert-warning');
        
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
        
        // veo un mensaje de exito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');
        
        // veo que en la tabla de la vista previa está el registro que acabo de cargar
        $I->see('1', 'tbody tr td');
        $I->see('0', 'tbody tr td');
    }
}