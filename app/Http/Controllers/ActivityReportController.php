<?php
namespace sanoha\Http\Controllers;

use Carbon\Carbon;
use sanoha\Http\Requests;
use Illuminate\Http\Request;
use sanoha\Models\ActivityReport;
use sanoha\Models\MiningActivity;
use sanoha\Http\Controllers\Controller;
use sanoha\Http\Requests\ActivityReportFormRequest;

class ActivityReportController extends Controller
{
    /** 
     * El centro de costos con el que se está trabajando
     * 
     * @var	string
     */
    private $cost_center_id;
    
    /**
     * Los parámetros para las búsquedas de registros en reportes
     * 
     * @var array
     */
    private $parameters = [];
    
    /**
     * Seteo los middleware de seguridad y las variable que guarda el id del centro
     * de costo con el que va a trabajar el resto de métodos...
     */
    public function __construct()
    {
        // el usuario debe haber iniciado sesión
        $this->middleware('auth');
        // comprueba que el usuario tenga permisos sobre el centro de costo seleccionado
        $this->middleware('checkCostCenter', ['except' => ['selectCostCenterView', 'setCostCenter']]);
        // control de acceso a los métodos de esta clase
        $this->middleware('checkPermmisions', ['except' => ['store', 'newStore', 'update', 'selectCostCenterView', 'setCostCenter']]);
        // el id del centro de costo elegido
        $this->cost_center_id = \Session::get('current_cost_center_id');
    }

    /**
     * Vista para elegir el centro de costo con el que se quiere trabajar en caso
     * de que la variable de session "current_cost_center_id" no esté seteada
     * por algún motivo.
     * 
     * @return \Illuminate\Http\Response
     */
    public function selectCostCenterView()
    {
        return view('activityReports.selectCostCenter');
    }
    
    /**
     * Guarda en una variable de sesión el centro de costo con el que va a trabajar
     * el controlador, el centro de costo es seleccionado a través de la barra de
     * navegación o la vista generada en selectCostCenterView().
     * 
     * @param	string		El id del centro de costo en la BD
     * 
     * @return	redirect	Redirecciona al vista index de reporte de actividades
     */
    public function setCostCenter($cost_center)
    {
        $cost_center = \sanoha\Models\CostCenter::findOrFail($cost_center);
        
        \Session::put('current_cost_center_name', $cost_center->name);
        \Session::put('current_cost_center_id', $cost_center->id);
        
        return redirect()->route('activityReport.individual');
    }

    /**
     * Muestro un informe con las actividades reportadas con precios, totales, etc...
     * A este reporte le llaman el de nómina...
     * Se podrá consultar filtrar por fechas, por nombres, apellidos, número de
     * documento, etc... El reporte predeterminado muestra las actividades
     * registradas del dia anterior.
     * 
     * @param	sanoha\Http\RequestsActivityReportFormRequest	$requests
     * @return	\Illuminate\Http\Response	
     */
    public function index(ActivityReportFormRequest $request)
    {
        $search_input = $request->all();
        
        $orderedActivities  = ActivityReport::sortedActivities($parameters = ActivityReport::configureParameters($request, $this->cost_center_id));
        $miningActivities   = MiningActivity::customOrder();
        
        return view('activityReports.index', compact('orderedActivities', 'miningActivities', 'parameters', 'search_input'));
    }
    
    /**
     * La vista calendario de las actividades mineras registradas
     * 
     * @return \Illuminate\Http\Response
     */
    public function calendar(ActivityReportFormRequest $request)
    {
        $search_input = $request->all();
        $activities = ActivityReport::getCalendarActivities($parameters = ActivityReport::configureParameters($request, $this->cost_center_id));

        return view('activityReports.calendar', compact('search_input', 'parameters', 'activities'));
    }
    
    /**
     * Muestra la vista que tiene el reporte con los registros individuales
     */
    public function individual(ActivityReportFormRequest $request)
    {
        $search_input = $request->all();
        
        $parameters = ActivityReport::configureParameters($request, $this->cost_center_id, ['start' => Carbon::now()->startOfYear()]);
        
        if(!$request->has('from') && !$request->has('to'))
            unset($parameters['from'], $parameters['to']);
        
        $activities = ActivityReport::individualSearch($parameters);

        return view('activityReports.individual', compact('activities', 'search_input', 'parameters'));
    }

    /**
     * Muestra el formulario para registrar una actividad minera de un trabajador.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request->all();

        $miningActivities = MiningActivity::customOrder();
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees($this->cost_center_id, null, null, [
            'employees' => [
                'position_id' => ActivityReport::getPositionsToInclude()
            ]
        ]);
        $orderedActivities = ActivityReport::sortedActivities($parameters = ActivityReport::configureParameters($request, $this->cost_center_id));

        return view('activityReports.create', compact('employees', 'miningActivities', 'orderedActivities', 'parameters', 'input'));
    }

    /**
     * Muestra el nuevo formulario de reporte de actividades mineras, con posibilidad
     * de reportar varias actividades a la vez en el mismo request.
     * 
     * @return \Illuminate\Http\Response
     */
    public function newCreateForm(Request $request)
    {
        // el modelo de actividades mineras
        $miningActivityModel = new ActivityReport;
        // las actividades mineras a registrar
        $miningActivities = MiningActivity::customOrder();

        // lista de empleados del centro de costo
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees($this->cost_center_id, null, null, [
            'employees' => [
                'position_id' => ActivityReport::getPositionsToInclude()
            ]
        ]);

        return view('activityReports.newCreateForm', compact('miningActivityModel', 'request', 'employees', 'miningActivities'));
    }

    /**
     * Procesa el nuevo formulario de reporte de actividades guardando varias actividades en
     * un sólo request
     * 
     * @return \Illuminate\Http\Response
     */
    public function newStore(ActivityReportFormRequest $request)
    {
        $data = $request->all();
        // contador de registros creados
        $count = 0;
        $data['reported_at'] = Carbon::createFromFormat('Y-m-d', $data['reported_at']);

        // obtengo las actividades mineras
        $miningActivities = MiningActivity::all();

        foreach ($miningActivities as $key => $activity) {
        //dd(array_key_exists('mining_activity['.$activity->id.']', $data), $request->get('mining_activity'));

            if (!array_key_exists($activity->id, $data['mining_activity']) || empty($data['mining_activity'][$activity->id])){
                continue;
            }

            $data['mining_activity_id'] = $activity->id;

            // debo saber si se ha reportado ya la actividad en el mismo día
            if ($reported_activity = ActivityReport::alreadyMiningActivityReported($data)) {
                return redirect()
                    ->back()
                    ->with('error', 'El trabajador ya reportó '.$reported_activity->miningActivity->name.' el día '.$reported_activity->reported_at->toDateString().'.');
            }

            // todo bien, ya puedo registrar la actividad, primero obtengo la info del empleado
            $employee = \sanoha\Models\Employee::findOrFail($data['employee_id']);
            
            // el precio automático a asignar a la actividad en caso de que el usuario
            // no haya especificado uno
            $historical_price = ActivityReport::getHistoricalActivityPrice($data['mining_activity_id'], $employee->sub_cost_center_id, $employee->id);
            
            $activityReport                       =    new ActivityReport;
            $activityReport->sub_cost_center_id   =    $employee->sub_cost_center_id;
            $activityReport->employee_id          =    $employee->id;
            $activityReport->mining_activity_id   =    $activity->id;
            $activityReport->quantity             =    $data['mining_activity'][$activity->id];
            $activityReport->price                =    !empty($data['mining_activity_price'][$activity->id]) && \Auth::getUser()->can('activityReport.assignCosts') ? $data['mining_activity_price'][$activity->id] : $historical_price;
            //$activityReport->worked_hours         =    0;
            $activityReport->comment              =    $data['comment'];
            $activityReport->reported_by          =    \Auth::getUser()->id;
            $activityReport->reported_at          =    $data['reported_at']->toDateTimeString();

            $activityReport->save() ? $count++ : '';

        }

        $count > 0
            ? $request->session()->flash('success', 'Actividades reportadas correctamente.')
            : $request->session()->flash('warning', 'No se reportaron actividades.');

        return redirect()->route('activityReport.newCreateForm');
    }

    /**
     * Guarda la nueva actividad del trabajador en la DB.
     * 
     * @param	sanoha\Http\Requests\ActivityReportFormRequest	$request
     * @return	\Illuminate\Http\Response
     */
    public function store(ActivityReportFormRequest $request)
    {
        $data = $request->all();
        
        $data['reported_at'] = Carbon::createFromFormat('Y-m-d', $data['reported_at']);
        
        // debo saber si se ha reportado ya la actividad en el mismo día
        if ($reported_activity = ActivityReport::alreadyMiningActivityReported($data)) {
            return redirect()
                ->back()
                ->with('error', 'El trabajador ya reportó '.$reported_activity->miningActivity->name.' el día '.$reported_activity->reported_at->toDateString().'.');
        }

        $employee = \sanoha\Models\Employee::findOrFail($data['employee_id']);
        
        // el precio automático a asignar a la actividad en caso de que el usuario
        // no haya especificado uno
        $historical_price = ActivityReport::getHistoricalActivityPrice($data['mining_activity_id'], $employee->sub_cost_center_id, $employee->id);
        
        $activity                       =    new ActivityReport;
        $activity->sub_cost_center_id   =    $employee->sub_cost_center_id;
        $activity->employee_id          =    $data['employee_id'];
        $activity->mining_activity_id   =    $data['mining_activity_id'];
        $activity->quantity             =    $data['quantity'];
        $activity->price                =    !empty($data['price']) && \Auth::getUser()->can('activityReport.assignCosts') ? $data['price'] : $historical_price;
        //$activity->worked_hours         =    $data['worked_hours'];
        $activity->comment              =    $data['comment'];
        $activity->reported_by          =    \Auth::getUser()->id;
        $activity->reported_at          =    $data['reported_at']->toDateTimeString();
        
        if ($activity->save()) {
            $request->session()->flash('success', 'Actividad Registrada Correctamente.');
            
            if (\Auth::getUser()->can('activityReport.assignCosts') == false && $historical_price == 0) {
                $request->session()->flash('warning', 'La actividad fue registrada, pero no se asignó el precio porque no hay históricos en que basar la selección.');
            }
        } else {
            $request->session()->flash('error', 'Error registrando la actividad.');
        }

        return redirect()->back()->withInput($request->only('employee_id'));
    }

    /**
     * Muestra el detalle de una actividad minera registrada.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = ActivityReport::findOrFail($id);
        
        return view('activityReports.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ActivityReportFormRequest $request)
    {
        $activity = ActivityReport::findOrFail($id);
        
        $input = $request->all();
        // parametros de búsqueda de activiades del empleado
        $parameters                     = ActivityReport::configureParameters($request, $this->cost_center_id);
        $parameters['employee_id']      = $activity->employee_id;
        $parameters['cost_center_id']   = $activity->subCostCenter->costCenter->id;
        $parameters['to']               = $activity->reported_at->endOfDay()->toDateTimeString();
        $parameters['from']             = $activity->reported_at->startOfDay()->toDateTimeString();
        
        // actividades registradas del empleado ordenadas
        $orderedActivities = ActivityReport::sortedActivities($parameters);

        // actividades mineras
        $miningActivities = MiningActivity::customOrder();
        
        // obtengo la lista de empleados relacinados al centro de costo y doy la opción de incluir
        // al empleado que hizo la labor minera si es que no se encuentra dentro de los empleados del
        // centro de costo
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees($this->cost_center_id, $activity->employee_id, null, [
            'employees' => [
                'position_id' => ActivityReport::getPositionsToInclude()
            ]
        ]);
        
        return view('activityReports.edit', compact('activity', 'employees', 'miningActivities', 'orderedActivities', 'parameters', 'input'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, ActivityReportFormRequest $request)
    {
        $activity                       = ActivityReport::findOrFail($id);
        $activity->employee_id          = $request->get('employee_id');
        $activity->mining_activity_id   = $request->get('mining_activity_id');
        $activity->quantity             = $request->get('quantity');
        $activity->price                = $request->get('price', 0);
        //$activity->worked_hours         = $request->get('worked_hours');
        $activity->reported_at          = $request->get('reported_at');
        $activity->comment              = $request->get('comment');

        $activity->save()
            ? $request->session()->flash('success', 'Actualización de Actividad Minera exitosa.')
            : $request->session()->flash('error', 'Ocurrió un error actualizando la actividad.');
    
        return redirect()->route('activityReport.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $id = $request->get('id', $id);

        ActivityReport::destroy($id)
            ? $request->session()->flash('success', is_array($id) && count($id) > 1
                ? 'Las actividades han sido movidas a la papelera correctamente.'
                : 'La actividad se ha movido a la papelera correctamente.')
            : $request->session()->flash('error', is_array($id)
                ? 'Ocurrió un error moviendo las actividades a la papelera.'
                : 'Ocurrió un problema moviendo la actividad a la papelera.') ;
        
        return redirect()->route('activityReport.individual');
    }
}
