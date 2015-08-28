<?php namespace ActivityReports;

use \FunctionalTester;
use \Carbon\Carbon;

use \sanoha\Models\User         as UserModel;

use \common\ActivityReports     as ActivityReportsCommons;
use \common\SubCostCenters      as SubCostCentersCommons;
use \common\CostCenters         as CostCentersCommons;
use \common\Employees           as EmployeesCommons;
use \common\MiningActivities    as MiningActivitiesCommons;
use \common\User                as UserCommons;
use \common\Permissions         as PermissionsCommons;
use \common\Roles               as RolesCommons;

class ReportMiningActivityCest
{
    public function _before(FunctionalTester $I)
    {
        //creo centros de costo
        $this->costCentersCommons = new CostCentersCommons;
        $this->costCentersCommons->createCostCenters();
        
        // creo subcentros de costo
        $this->subCostCentersCommons = new SubCostCentersCommons;
        $this->subCostCentersCommons->createSubCostCenters();
        
        // creo los empleados
        $this->employeeCommons = new EmployeesCommons;
        $this->employeeCommons->createMiningEmployees();
        
        // creo actividades mineras
        $this->miningActivities = new MiningActivitiesCommons;
        $this->miningActivities->createMiningActivities();

        // creo los permisos para el módulo de reporte de actividades mineras
        $this->permissionsCommons = new PermissionsCommons;
        $this->permissionsCommons->createActivityReportsModulePermissions();
        
        // creo los roles de usuario y añado todos los permisos al rol de administrador
        $this->rolesCommons = new RolesCommons;
        $this->rolesCommons->createBasicRoles();
        
        // creo el usuairo administrador
        $this->userCommons = new UserCommons;
        $this->user = $this->userCommons->createAdminUser();
        $this->userCommons->createUsers();
        
        // le asigno los centros de costo al usuario administrador
        $this->user->subCostCenters()->sync([1,2,3,4]); // estos son los id's de los subcentros de los primeros dos proyectos o centros de costo
        
        // creo algunos reportes de actividades mineras
        $this->activityReportsCommons = new ActivityReportsCommons;
        //$this->activityReportsCommons->createActivityReports(3);

        $I->amLoggedAs($this->userCommons->adminUser);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     *  Pruebo el formulario de reporte de actividad o labor minera
     * 
     * @param
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
        
        // creo un registro antiguo para que tenga referencia para asígnar el precio
        // de la actividad que voy a registrar
        \sanoha\Models\ActivityReport::create([
            'sub_cost_center_id'    =>  1,
            'employee_id'           =>  1,
            'mining_activity_id'    =>  2,
            'quantity'              =>  2,
            'price'                 =>  '5000',
            'worked_hours'          =>  4,
            'comment'               =>  'test comment',
            'reported_by'           =>  1,
            'reported_at'           =>  '2015-07-05 10:00:00'
        ]);
        
        // -----------------------
        // --- Empieza el test ---
        // -----------------------
        
        $I->am('soy un supervisor del Proyecto Beteitiva');
        $I->wantTo('registrar la actividad minera de un trabajador de mi proyecto');
        
        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        // doy clic al botón de registrar una actividad minera
        $I->click('Registrar Labor Minera', 'a');
        
        // veo que estoy en la url indicada
        $I->seeCurrentUrlEquals('/activityReport/create');
        
        // veo que el título de la página es "Registrar Labor Minera"
        $I->see('Registrar Labor Minera', 'h1');
        
        // veo que está divido en secciones el formulario, un fielset donde
        // están los compos para el registro y otro fieldset donde está la vista previa de
        // los datos cargados al trabajador
        $I->see('Detalles de Labor', 'fieldset legend');
        $I->see('Vista Previa de Actividades', 'fieldset legend');
        
        // veo que hay un select con los nombres de los trabajadores del centro
        // de costos que seleccioné
        $I->see('Trabajador 1 B1', 'select optgroup option');
        $I->see('Trabajador 2 B2', 'select optgroup option');
        
        // veo que NO hay un select con las actividades mineras posibles a reportar,
        // debo elejir primero al trabajador
        $I->dontSeeElement('input', ['type' => 'checkbox', 'checked' => 'checked']); // por defecto está marcado
        $I->dontSeeElement('select', ['name' => 'mining_activity_id']);
        $I->dontSeeElement('input', ['name' => 'quantity']);
        $I->dontSeeElement('input', ['name' => 'price']);
        $I->dontSeeElement('input', ['name' => 'reported_at']);
        $I->dontSeeElement('textarea', ['name' => 'comment']);
        $I->dontSeeElement('button', ['type' => 'submit']);
        
        // veo que en la vista preliminar tengo un mensaje que dice "Selecciona un trabajador"
        // pues no he seleccionado alguno para ver los datos de las labores mineras que se le
        // han cargado del día en curso
        $I->see('Selecciona un trabajador...', '.alert-warning');
        
        // veo que el atributo action del formulario es /localhost/activityReport/create,
        // para que en la siguiente carga pueda pueda cargar la vista previa de los datos
        // del empleado
        $I->seeElement('form', ['method'    =>  'GET']); /* url por verificar */
        
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
        $I->dontSeeElement('input', ['name' => 'price', 'step' => '1']); // NO VEO el input para digitar el precio
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
            'reported_at'           =>  \Carbon\Carbon::now()->toDateTimeString(),
            'comment'               =>  'Comentario de prueba'
        ];
        
        $I->dontSeeRecord('activity_reports', $activityToReport);
        
        $I->submitForm('form', $activityToReport, 'Registrar');
        
        // veo que estoy de nuevo en la página de registro de actividad minera
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id=1');
        
        // no debe haber mensajes de alerta o error
        $I->dontSeeElement('div', ['class' => 'alert alert-warning alert-dismissible']);
        $I->dontSeeElement('div', ['class' => 'alert alert-danger alert-dismissible']);
        
        // veo un mensaje de éxito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');
        
        // veo que en la base de datos efectivamente existe el registro que acabo de crear
        $I->seeRecord('activity_reports', $activityToReport);
        
        // refresco la página
        $I->amOnPage('/activityReport/create?employee_id=1');
        
        // veo que en la tabla de la vista previa está el registro que acabo de cargar, con el precio histórico que se ha asignado en ese centro de costo a esa actividad
        $I->see('2.5', 'tbody tr td');
        $I->see('12.500', 'tbody tr td'); // 2.5 a $5.000 cada actividad que fue lo que se ha pagado antes
        
    }
    
    /**
     * 
     */
    public function testErrorMessages(FunctionalTester $I)
    {
        // info del trabajador
        $employee = \sanoha\Models\Employee::first();
        
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
        $I->see('Registrar Labor Minera', 'h1');
       
        // selecciono al trabajador y envío el formulario para que me cargue los demás campos
        $I->submitForm('form', ['employee_id' => $employee->id]);
        
        // veo que la url cambia un poco, pues tiene el id del empleado seleccionado
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employee->id);
        
        // ----------------------------
        // ----- primera prueba -------
        // ----------------------------
        
        // aquí los datos erroneos con lo que voy a enviar el formulrio
        $report = [
            'employee_id'           =>  876, // envío el formulario con la info de un empleado que no existe
            'mining_activity_id'    =>  2649, // esta actividad tampoco existe
            'quantity'              =>  600, // la cantidad sobrepasa lo permitido por la actividad minera seleccionada
            'comment'               =>  'Test coment with dots...' // los comentarios no deben tener puntos
        ];
       
        // envío el formulario
        $I->submitForm('form', $report, 'Registrar');
       
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employee->id);
       
        // debo ver el título correspondiente
        $I->see('Registrar Labor Minera', 'h1');
       
        // veo que el formulario tiene unos errores
        $I->seeFormHasErrors();
       
        // veo que cada error error es mostrado en una capa con el correspondiente
        // estilo de error o alerta
        $I->see('Trabajador inválido.', '.text-danger');
        $I->see('Labor minera inválida.', '.text-danger');
        $I->see('El comentario sólo debe contener letras y/o espacios.', '.text-danger');
        // no veo error en la cantidad pues no tiene referencia válida a la labor minera para obtener el límite
        $I->dontSee('Selecciona la cantidad.', '.text-danger');
        
        
        
        // ----------------------------
        // ----- segunda prueba -------
        // ----------------------------
        
        $I->wantTo('probar los mensajes de error en identifidores de trabajador y labor minera');
        
        // aquí los datos erroneos con lo que voy a enviar el formulrio
        $report = [
            'employee_id'           =>  'mm', // empleado con formato de id inválido
            'mining_activity_id'    =>  'nn', // actividad con formato erróneo
            'quantity'              =>  '', // la cantidad es obligatoria
            'comment'               =>  ''
        ];
       
        // envío el formulario
        $I->submitForm('form', $report, 'Registrar');
       
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employee->id);
       
        // debo ver el título correspondiente
        $I->see('Registrar Labor Minera', 'h1');
       
        // veo que el formulario tiene unos errores
        $I->seeFormHasErrors();
       
        // veo que cada error error es mostrado en una capa con el correspondiente
        // estilo de error o alerta
        $I->see('Identificador de empleado inválido.', '.text-danger');
        $I->see('Identificador de labor minera inválido.', '.text-danger');
        // ya no veo mensaje de error del comentario
        $I->dontSee('El comentario sólo debe contener letras y/o espacios.', '.text-danger');
        // no veo error en la cantidad pues no tiene referencia válida a la labor minera para obtener el límite
        $I->see('Debes digitar la cantidad.', '.text-danger');
        
        
        // ----------------------------
        // ------ tercera prueba ------
        // ----------------------------
        
        $I->wantTo('probar los mensajes de error en la cantidad de la labor minera reportada');
        
        // aquí los datos erroneos con lo que voy a enviar el formulrio
        $report = [
            'employee_id'           =>  1, // envío el formulario con la info de un empleado que no existe
            'mining_activity_id'    =>  1, // esta actividad tampoco existe
            'quantity'              =>  600, // la cantidad sobrepasa lo permitido por la actividad minera seleccionada
            'comment'               =>  'Comentario de prueba' // los comentarios no deben tener puntos
        ];

        // envío el formulario
        $I->submitForm('form', $report, 'Registrar');
       
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employee->id);
       
        // debo ver el título correspondiente
        $I->see('Registrar Labor Minera', 'h1');
       
        // veo que el formulario tiene unos errores
        //$I->seeFormHasErrors();
       
        // veo que cada error error es mostrado en una capa con el correspondiente
        // estilo de error o alerta
        $I->dontSee('Identificador de empleado inválido.', '.text-danger');
        $I->dontSee('Identificador de labor minera inválido.', '.text-danger');
        // no veo mensaje de error del comentario
        $I->dontSee('El comentario sólo debe contener letras y/o espacios.', '.text-danger');
        // ahora si veo error en la cantidad pues tiene referencia válida a la labor minera para obtener el límite
        $I->see('El rango permitido es entre 0.1 y 100.', '.text-danger');
    }
    
    /**
     * Pruebo que determinado usuario puede asignar costos y otro no dependiendo de
     * los permisos que tenga asignados...
     */
    public function assignCosts(FunctionalTester $I)
    {
        $employee = \sanoha\Models\Employee::find(1);
        // ------------------------------
        // ---- usuario CON permisos ----
        // ------------------------------
        $I->am('soy un usuario administrador con permiso para asignar costos');
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
        $I->submitForm('form', ['employee_id' => $employee->id]);
        
        // y veo que todos los campos están presentes, incluido el de asignar
        // costo a la actividad pues yo tengo asignado tal permiso
        $I->seeElement('select', ['name'  =>  'employee_id']);
        $I->seeElement('select', ['name'  =>  'mining_activity_id']);
        $I->seeElement('input', ['name'  =>  'quantity']);
        $I->seeElement('input', ['name'  =>  'price']);
        $I->seeElement('textarea', ['name'  =>  'comment']);
        
        // la info del reporte a enviar
        $data = [
            'employee_id'           =>      $employee->id,
            'mining_activity_id'    =>      2,
            'quantity'              =>      4,
            'price'                 =>      15000,
            'reported_at'           =>      \Carbon\Carbon::now()->toDateTimeString(),
            'comment'               =>      'test comment'
        ];
        
        // envío el formluario
        $I->submitForm('form', $data);
        
        // veo en la base de datos el nuevo registro
        $I->seeRecord('activity_reports', $data);
        
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employee->id);
        
        // veo un mensaje de exito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');
        
        // veo que en la tabla de la vista previa está el registro que acabo de cargar
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
        $I->amLoggedAs($this->userCommons->adminUser); // ya se lequitaron los permisos
        
        // estoy en el home
        $I->amOnPage('/home');
        
        // selecciono el centro de costos con el que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        
        // pulso el botón para reportar una nueva actividad
        $I->click('Registrar Labor Minera', 'a');
        
        // selecciono un trabajador para ver los demás campos
        $I->submitForm('form', ['employee_id' => $employee->id]);
        
        // y veo que todos los campos están presentes, menos el de asignar
        // costo a la actividad pues yo no tengo asignado tal permiso
        $I->seeElement('select', ['name'  =>  'employee_id']);
        $I->seeElement('select', ['name'  =>  'mining_activity_id']);
        $I->seeElement('input', ['name'  =>  'quantity']);
        $I->dontSeeElement('input', ['name'  =>  'price']);
        $I->seeElement('textarea', ['name'  =>  'comment']);
        
        // la info del reporte a enviar
        $data = [
            'employee_id'           =>      $employee->id,
            'mining_activity_id'    =>      4,
            'quantity'              =>      3,
            'reported_at'           =>      \Carbon\Carbon::now()->toDateTimeString(),
            //'price'               =>      15000, // no puedo asignar este campo
        ];
        
        // envío el formluario
        $I->submitForm('form', $data);
        
        // veo en la base de datos el nuevo registro
        $I->seeRecord('activity_reports', $data+['price' => 0]);
        
        // como no se asignó precio por el usuario y porque no hay histórico, entonces
        // debo ver un mensage de alerta donde me informe que no se ha asignado el precio
        // automáticamente porque no hay históricos en que basar la selección del precio
        $I->see('La actividad fue registrada, pero no se asignó el precio porque no hay históricos en que basar la selección.', '.alert-warning');
        
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employee->id);
        
        // veo un mensaje de exito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');
        
        // veo que en la tabla de la vista previa está el registro que acabo de cargar
        $I->see('3', 'tbody tr td');
        $I->see('0', 'tbody tr td');
    }
}