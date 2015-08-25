<?php namespace sanoha\Http\Controllers;

use sanoha\Http\Requests;
use sanoha\Http\Controllers\Controller;
use sanoha\Http\Requests\ActivityReportFormRequest;

use Carbon\Carbon;
use Illuminate\Http\Request;

use sanoha\Models\ActivityReport;
use sanoha\Models\MiningActivity;

class ActivityReportController extends Controller {
	
	/** 
	 * El centro de costos con el que se está trabajando
	 * 
	 * @var	string
	 */
	private $cost_center_id;
	
	/**
	 * 
	 */ 
	public function __construct()
	{
		// me aseguro que se haya elejido un centro de costos y que el usuario tenga acceso
		// a ese centro
		$this->middleware('checkCostCenter', ['except' => ['selectCostCenterView', 'setCostCenter']]);
		
		// control de acceso a los métodos de esta clase
		$this->middleware('checkPermmisions', ['except' => ['store','update','selectCostCenterView','setCostCenter']]);
		
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
	 * @param	string		El id del centro de costos de la DB
	 * @return	redirect	Redirecciona al vista index de reporte de actividades
	 */ 
	public function setCostCenter($cost_center){
		\Session::put('current_cost_center_id', $cost_center);
		return redirect()->route('activityReport.index');
	}

	/**
	 * Muestro un informe con las actividades reportadas, en donde se podrá consultar
	 * filtrar por fechas, por nombres, apellidos, número de documento, etc... El
	 * reporte predeterminado muestra las actividades registradas del dia
	 * anterior.
	 * 
	 * @param	sanoha\Http\RequestsActivityReportFormRequest	$requests
	 * @return	\Illuminate\Http\Response	
	 */
	public function index(ActivityReportFormRequest $request)
	{
		$search_input = $request->all();
        
        $start = Carbon::createFromFormat('Y-m-d', $request->has('from') ? $request->get('from') : date('Y-m-d'))->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $request->has('to') ? $request->get('to') : date('Y-m-d'))->endOfDay();
        
        if(!$request->has('from')){
        	$start->subDays(1);
        	$end->subDays(1);
        }
        
        $parameters['employee'] 		= !empty($request->get('find')) ? $request->get('find') : null;
		$parameters['from'] 			= $start;
		$parameters['to'] 				= $end;
		$parameters['costCenter_id'] 	= $this->cost_center_id;
		$parameters['costCenter_name'] 	= \sanoha\Models\CostCenter::findOrFail($this->cost_center_id)->name;

		$activities = ActivityReport::getActivities($parameters);
		
		$orderedActivities = ActivityReport::sortActivities($activities);
		
		$miningActivities = MiningActivity::orderBy('short_name')->get();
		
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
		
		$start = Carbon::createFromFormat('Y-m-d', $request->has('from') ? $request->get('from') : date('Y-m-d'))->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $request->has('to') ? $request->get('to') : date('Y-m-d'))->endOfDay();
        
        if(!$request->has('from')){
        	$start->startOfMonth();
        	$end->endOfMonth();
        }
        
        $parameters['employee'] 		= $request->get('find', null);
		$parameters['from'] 			= $start;
		$parameters['to'] 				= $end;
		$parameters['costCenter_id'] 	= $this->cost_center_id;
		$parameters['costCenter_name'] 	= \sanoha\Models\CostCenter::findOrFail($this->cost_center_id)->name;
		
		$activities = ActivityReport::getCalendarActivities($parameters);

		$activities = json_encode($activities);
		
		return view('activityReports.calendar', compact('search_input', 'parameters', 'activities'));
	}

	/**
	 * Muestra el formulario para registrar una actividad minera de un trabajador.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
        $start = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->endOfDay();
        
        // parametros de búsqueda de activiades del empleado
        $parameters 					= [];
        $parameters['employee_id'] 		= $request->get('employee_id', null);
		$parameters['from'] 			= $start;
		$parameters['to'] 				= $end;
		$parameters['costCenter_id'] 	= $this->cost_center_id;
		$parameters['costCenter_name'] 	= \sanoha\Models\CostCenter::findOrFail($this->cost_center_id)->name;
		
		$employee = empty($parameters['employee_id']) ? null : \sanoha\Models\Employee::findOrFail($parameters['employee_id']);
	
		$subCostCenterEmployees = \sanoha\Models\SubCostCenter::where('cost_center_id', $this->cost_center_id)->with('employees')->get();
		
		$employees = [];
		
		foreach ($subCostCenterEmployees as $key => $subCostCenter) {
			//$employees[$subCostCenter->name] = [];
			$employees[$subCostCenter->name] = array();
			foreach ($subCostCenter->employees as $key_employee => $employee) {
				$employees[$subCostCenter->name][$employee->id] = $employee->fullname;
			}
			
		}

		// actividades mineras
		$miningActivities = MiningActivity::orderBy('short_name')->get();
		
		// actividades registradas del empleado
		$employee_activities = ActivityReport::getActivities($parameters);
		
		// actividades registradas del empleado ordenadas
		$employee_activities = ActivityReport::sortActivities($employee_activities);
		
		$input = $request->all();

		return view('activityReports.create', compact('employees', 'miningActivities', 'employee', 'employee_activities', 'parameters', 'input'));
	}

	/**
	 * Guarda la nueva actividad del trabajador en la DB.
	 *
	 * @return	\Illuminate\Http\Response
	 */
	public function store(ActivityReportFormRequest $request)
	{
		$data = $request->all();

		$employee = \sanoha\Models\Employee::findOrFail($data['employee_id']);
		
		$activity 						=	new ActivityReport;
		$activity->sub_cost_center_id	=	$employee->sub_cost_center_id;
		$activity->employee_id 			=	$data['employee_id'];
		$activity->mining_activity_id	=	$data['mining_activity_id'];
		$activity->quantity 			=	$data['quantity'];
		/**
		 * ----------------------------------------
		 * ---------------- OJO AQUÍ --------------
		 * ----------------------------------------
		 * El precio no puede ser asignado por usuarios sin los permisos debidos, hay que
		 * controlar esta parte verificando primero si se tienen los permisos para
		 * asignar el precio, el valor por defecto en la base de datos es 0
		 */
		$activity->price 				=	isset($data['price']) ? $data['price'] : '';
		$activity->comment 				=	$data['comment'];
		$activity->reported_by 			=	\Auth::getUser()->id;
		$activity->reported_at 			=	$data['reported_at'];
		
		$activity->save()
			? \Session::flash('success', 'Actividad Registrada Correctamente.')
			: \Session::flash('error', 'Error registrando la actividad.');

		return redirect()->back()->withInput($request->only('employee_id'));
	}

	/**
	 * Muestra el detalle de una actividad minera registrada en la DB.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$activity = ActivityReport::findOrFail($id);
		
		$start = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->endOfDay();
        
        // parametros de búsqueda de activiades del empleado
        $parameters 					= [];
        $parameters['employee_id'] 		= $activity->employee_id;
		$parameters['from'] 			= $activity->created_at->startOfDay();
		$parameters['to'] 				= $activity->created_at->endOfDay();
		$parameters['costCenter_id'] 	= $this->cost_center_id;
		$parameters['costCenter_name'] 	= \sanoha\Models\CostCenter::findOrFail($this->cost_center_id)->name;
		
		return view('activityReports.show', compact('activity', 'subCostCenterEmployees', 'parameters'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$activity = ActivityReport::findOrFail($id);
		
		$start = $activity->reported_at->startOfDay();
        $end = $activity->reported_at->endOfDay();
        
        // parametros de búsqueda de activiades del empleado
        $parameters 					= [];
        $parameters['employee_id'] 		= $activity->employee_id;
		$parameters['from'] 			= $start;
		$parameters['to'] 				= $end;
		$parameters['costCenter_id'] 	= $this->cost_center_id;
		$parameters['costCenter_name'] 	= \sanoha\Models\CostCenter::findOrFail($this->cost_center_id)->name;
		
		$subCostCenterEmployees = \sanoha\Models\SubCostCenter::where('cost_center_id', $this->cost_center_id)->with('employees')->get();
		
		$employees = [];
		
		foreach ($subCostCenterEmployees as $key => $subCostCenter) {
			//$employees[$subCostCenter->name] = [];
			$employees[$subCostCenter->name] = array();
			foreach ($subCostCenter->employees as $key_employee => $employee) {
				$employees[$subCostCenter->name][$employee->id] = $employee->fullname;
			}
			
		}

		if (array_key_exists($activity->employee_id, $employees) !== true){
			$employees = [$activity->employee->id => $activity->employee->fullname]+$employees;
		}
		// actividades mineras
		$miningActivities = MiningActivity::orderBy('short_name')->get();
		
		// actividades registradas del empleado
		$employee_activities = ActivityReport::getActivities($parameters);
		
		// actividades registradas del empleado ordenadas
		$employee_activities = ActivityReport::sortActivities($employee_activities);
		
		//dd($employee_activities);
		$input = $request->all();
		
		return view('activityReports.edit', compact('activity', 'employees', 'miningActivities', 'employee_activities', 'parameters', 'input'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, ActivityReportFormRequest $request)
	{	
		$activity						= ActivityReport::findOrFail($id);
		$activity->employee_id			= $request->get('employee_id');
		$activity->mining_activity_id 	= $request->get('mining_activity_id');
		$activity->quantity 			= $request->get('quantity');
		$activity->price 				= $request->has('price') ? $request->get('price') : 0;
		$activity->comment				= $request->get('comment');
		$activity->save() ? \Session::flash('success', 'Actualización de Actividad Minera exitosa.') : \Session::flash('error', 'Ocurrió un error actualizando la actividad.') ;
	
		return redirect()->route('activityReport.show', $id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
