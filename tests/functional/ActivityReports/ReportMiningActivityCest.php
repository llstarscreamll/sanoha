<?php namespace ActivityReports;

use \FunctionalTester;
use \Carbon\Carbon;
use \Users\_common\UserCommons;
use \ActivityReports\_common\ActivityReportsCommons;

class ReportMiningActivityCest
{
    public function _before(FunctionalTester $I)
    {
        $this->userCommons = new UserCommons;
        $this->userCommons->createAdminUser();
        $this->userCommons->haveUsers(10); // creo 10 usuarios
        $this->userCommons->haveEmployees(10); // crea 10 empleados + 2 por defecto
        $this->userCommons->haveMiningActivities();
        
        $I->amLoggedAs($this->userCommons->adminUser);
        $I->seeAuthentication();
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
        
        // necesito los datos del proyecto
        $project = \sanoha\Models\CostCenter::find($project_id);
        
        // necesito la lista de trabajadores asociados a ese proyecto
        $employees = \sanoha\Models\Employee::where('cost_center_id', '=', $project_id)->get();
        
        // necesito la lista de labores mineras que puedo registrar
        $labors = \sanoha\Models\MiningActivity::all();
        
        // -----------------------
        // --- Empieza el test ---
        // -----------------------
        
        $I->am('soy un supervisor del '. $project->name); // Projecto Beteitiva
        $I->wantTo('registrar la actividad minera de un trabajador de mi proyecto');
        
        // estoy en el home del sistema
        $I->amOnPage('/home');
        
        // hago clic en el proyecto que quiero trabajar
        $I->click($project->name, 'a');
        
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
        foreach ($employees as $employee) {
            $I->see($employee->name, 'select option');
        }
        
        // veo que NO hay un select con las actividades mineras posibles a reportar,
        // debo elejir primero al trabajador
        $I->dontSeeElement('select', ['name' => 'mining_activity_id']);
        
        // veo que NO hay un campo donde digitar la cantidad o medida de la
        // actividad a reportar, debo elejir primero al trabajador
        $I->dontSeeElement('input', ['name' => 'quantity']);
        
        // veo que NO está el campo para asignar el precio negociado de la actividad,
        // pues el precio lo asigna el ingenero del área técnica a cargo de dicho proyecto
        $I->dontSeeElement('input', ['name' => 'price']);
        
        // veo que NO hay un textarea para digitar los comentarios u observaciones
        // de la actividad minera a registrar, debo elejir primero al trabajador
        $I->dontSeeElement('textarea', ['name' => 'comment']);

        
        // NO veo el botón de registrar la actividad para enviar el formulario,
        // el formulario será enviado cuando se seleccione el trabajador de la lista
        // automáticamente
        $I->dontSeeElement('button', ['type' => 'submit']);
        
        // veo que en la vista preliminar tengo un mensaje que dice "Selecciona un trabajador"
        // pues no he seleccionado alguno para ver los datos de las labores mineras que se le
        // han cargado del día en curso
        $I->see('Selecciona un trabajador...', '.alert-warning');
        
        // veo que el atributo action del formulario es /localhost/activityReport/create,
        // para que en la siguiente carga pueda pueda cargar la vista previa de los datos
        // del empleado
        $I->seeElement('form', ['method'    =>  'GET']); /* url por verificar */
        
        // selecciono un trabajador de la lista
        $I->selectOption('employee_id', $employees->first()->name . ' ' .$employees->first()->lastname);
        
        // selecciono un trabajador de la lista y hago la simulación de envío del formulario
        // aunque no tengo botón, esto se hará con javascript en el onChange del select
        $I->submitForm('form', ['employee_id' => $employees->first()->id]);
        
        // la página se recarga al elejir al trabajador, veo que estoy de nuevo en
        // la misma página pero con el parámetro del trabajador seleccionado
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employees->first()->id);
        
        // veo que tengo seleccionado el empleado que elejú antes
        $I->seeOptionIsSelected('form select[name=employee_id]', $employees->first()->fullname);
        
        // ahora si veo los campos faltantes del formulario para poder registrar la actividad
        $I->seeElement('select', ['name' => 'mining_activity_id']); // el select con las labores mineras
        // nuevo requerimento, 08/07/2015, los precios se deben asiganr cada 100 en vez de cada 500
        $I->seeElement('input', ['name' =>  'quantity']); // el input para digitar la cantidad
        
        // ------------------------------------------------------------------------------------------
        // Nuevo Requerimiento...
        //
        // el supervisor no puede asignar precios de las labores mineras registradas, por lo tanto
        // no puede ver el campo price o precio en el formulario, esto queda para un proceso aparte
        // ------------------------------------------------------------------------------------------
        $I->dontSeeElement('input', ['name' => 'price', 'step' => '100']); // NO VEO el input para digitar el precio
        $I->seeElement('button', ['type' => 'submit']); // el botton para enviar el formulario
        
        // ahora si veo la tabla donde se mostrarán los registros de las actividades del trabajador
        $I->seeElement('table', ['class' => 'table table-hover table-bordered table-vertical-align-middle']);
        
        //veo que el nombre corto de todas las actividades mineras están en
        // la cabecera de la tabla, pero tienen su nombre completo en el atributo title
        foreach ($labors as $activity) {
            $I->see($activity->short_name, 'th');
            $I->seeElement('th span', ['title' => $activity->name, 'data-toggle' => 'tooltip']);
        }
        
        // veo el nombre del trabajador en la tabla
        $I->see($employees->first()->fullname, 'tbody tr:first-child td:first-child');
        
        // veo que hay un mensaje de alerta que me dice que nada se le ha cargado al trabajador
        $I->see('No hay actividades registradas...', 'div.alert-warning');
        
        // lleno y envío el fomulario registrando una nueva actividad del trabajador
        $activityToReport = [
            'employee_id'           =>  $employees->first()->id,
            'mining_activity_id'    =>  1, // por ejemplo
            'quantity'              =>  2,
            'price'                 =>  0, // es el valor por defecto, pues el supervisor no lo puede asignar
            'comment'               =>  'Comentario de prueba'
        ];
        
        $I->submitForm('form', $activityToReport, 'Registrar');
        
        // veo que en la base de datos efectivamente existe el registro que acabo de crear
        $I->seeRecord('activity_reports', $activityToReport);
        
        // veo que estoy de nuevo en la página de registro de actividad minera
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employees->first()->id);
        
        // veo u mensaje de éxito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');
        
        // no veo mesajes de error o alerta por nunguna parte
        $I->dontSeeElement('div', ['class' => 'alert-danger']);
        $I->dontSeeElement('div', ['class' => 'alert-warning']);

        // veo que en la tabla de la vista previa está el registro que acabo de cargar, como
        // consecuencia debe estar seleccionado el trabajador automáticamente
        $I->see('2', 'tbody tr:last-child td:nth-child(2)'); // 2 porque la primera columna es para el label total y la segunda es donde debe estar la labor registrada
        
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
        $I->see('El rango permitido es entre 1 y 10.', '.text-danger');
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
            'quantity'              =>      3,
            'price'                 =>      15000,
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
            'mining_activity_id'    =>      2,
            'quantity'              =>      3,
            //'price'                 =>      15000, // no puedo asignar este campo
        ];
        
        // envío el formluario
        $I->submitForm('form', $data);
        
        // veo en la base de datos el nuevo registro
        $I->seeRecord('activity_reports', $data+['price' => '']);
        
        // veo que estoy en la misma url
        $I->seeCurrentUrlEquals('/activityReport/create?employee_id='.$employee->id);
        
        // veo un mensaje de exito en la operación
        $I->see('Actividad Registrada Correctamente.', '.alert-success');
        
        
        
    }
}