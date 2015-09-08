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
	 * Seteo los middleware de seguridad y las variable que guarda el id del centro
	 * de costo con el que va a trabajar el resto de métodos...
	 */
	public function __construct()
	{
		// me aseguro que se haya elejido un centro de costos y que el usuario tenga acceso
		// a ese centro
		$this->middleware('checkCostCenter', ['except' => ['selectCostCenterView', 'setCostCenter']]);
		
		// control de acceso a los métodos de esta clase
		$this->middleware('checkPermmisions', ['except' => ['store','update','selectCostCenterView','setCostCenter']]);
		
		// el id del centro de costo de trabajo elegido
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
        
        $start = Carbon::createFromFormat('Y-m-d', $request->has('from') ? $request->get('from') : \Carbon\Carbon::now()->startOfMonth()->toDateString())->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $request->has('to') ? $request->get('to') : \Carbon\Carbon::now()->toDateString())->endOfDay();
        
        $parameters['employee'] 		= !empty($request->get('find')) ? $request->get('find') : null;
		$parameters['from'] 			= $start;
		$parameters['to'] 				= $end;
		$parameters['costCenter_id'] 	= $this->cost_center_id;
		$parameters['costCenter_name'] 	= \sanoha\Models\CostCenter::findOrFail($this->cost_center_id)->name;

		$activities = ActivityReport::getActivities($parameters);
		
		$orderedActivities = ActivityReport::sortActivities($activities);
		
		$miningActivities = MiningActivity::customOrder();
		
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
		
		$start = Carbon::createFromFormat('Y-m-d', $request->has('from') ? $request->get('from') : \Carbon\Carbon::now()->startOfMonth()->toDateString())->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $request->has('to') ? $request->get('to') : \Carbon\Carbon::now()->toDateString())->endOfDay();
        
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
	 * Muestra la vista que tiene el reporte con los registros individuales
	 */
	public function individual(Request $request)
	{
		$cost_center_id = $this->cost_center_id;
		
		$search_input = $request->all();
		
		$start = Carbon::createFromFormat('Y-m-d', $request->has('from') ? $request->get('from') : \Carbon\Carbon::now()->startOfYear()->toDateString())->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $request->has('to') ? $request->get('to') : \Carbon\Carbon::now()->toDateString())->endOfDay();

        $parameters['employee'] 		= !empty($request->get('find')) ? $request->get('find') : null;
		$parameters['from'] 			= $start;
		$parameters['to'] 				= $end;
		$parameters['cost_center_id'] 	= $this->cost_center_id;
		$parameters['cost_center_name'] 	= \sanoha\Models\CostCenter::findOrFail($this->cost_center_id)->name;
		
		$activities = ActivityReport::where('reported_at', '>=', $parameters['from'])
			->where('reported_at', '<=', $parameters['to'])
			->orderBy('updated_at', 'desc')
			->whereHas('employee', function($q) use ($parameters)
				{
				    $q->where(function($q) use ($parameters){
				    	$q->where('name', 'like', '%'.$parameters["employee"].'%')
				    		->orWhere('lastname', 'like', '%'.$parameters["employee"].'%')
				    		->orWhere('identification_number', 'like', '%'.$parameters["employee"].'%');
				    });
				
				})
			->whereHas('subCostCenter', function($q) use ($parameters){
				$q->where('cost_center_id', $parameters['cost_center_id']);
			})
			->paginate(15);
		
		return view('activityReports.individual', compact('activities', 'search_input', 'parameters'));
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
		$miningActivities = MiningActivity::customOrder();
		//dd($miningActivities);
		// actividades registradas del empleado
		$orderedActivities = ActivityReport::getActivities($parameters);
		
		// actividades registradas del empleado ordenadas
		$orderedActivities = ActivityReport::sortActivities($orderedActivities);
		
		$input = $request->all();

		return view('activityReports.create', compact('employees', 'miningActivities', 'employee', 'orderedActivities', 'parameters', 'input'));
	}

	/**
	 * Guarda la nueva actividad del trabajador en la DB.
	 *
	 * @return	\Illuminate\Http\Response
	 */
	public function store(ActivityReportFormRequest $request)
	{
		$data = $request->all();
		$data['reported_at'] = \Carbon\Carbon::createFromFormat('Y-m-d', $data['reported_at']);
		//dd($data);
		
		if($reported_activity = \sanoha\Models\ActivityReport::where(function($q) use ($data){
			$q	->where('employee_id', $data['employee_id'])
				->where('mining_activity_id', $data['mining_activity_id'])
				->whereBetween('reported_at', [$data['reported_at']->copy()->startOfDay()->toDateTimeString(), $data['reported_at']->copy()->endOfDay()->toDateTimeString()]);
		})->first())
			return redirect()->back()->with('error', 'El trabajador ya reportó '.$reported_activity->miningActivity->name.' el día '.$reported_activity->reported_at->toDateString().'.');

		$employee = \sanoha\Models\Employee::findOrFail($data['employee_id']);
		$historical_price = \sanoha\Models\ActivityReport::getHistoricalActivityPrice($data['mining_activity_id'], $employee->sub_cost_center_id);
		
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
		$activity->price 				=	isset($data['price']) && !empty($data['price']) ? $data['price'] : $historical_price;
		$activity->worked_hours			=	$data['worked_hours'];
		$activity->comment 				=	$data['comment'];
		$activity->reported_by 			=	\Auth::getUser()->id;
		$activity->reported_at 			=	$data['reported_at']->toDateTimeString();
		
		if($activity->save()){
			\Session::flash('success', 'Actividad Registrada Correctamente.');
			
			if(!isset($data['price']) && $historical_price == 0)
				\Session::flash('warning', 'La actividad fue registrada, pero no se asignó el precio porque no hay históricos en que basar la selección.');
			
		}else
			\Session::flash('error', 'Error registrando la actividad.');

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
		$activity->reported_at = $activity->reported_at->toDateString();

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
		$activity->worked_hours			= $request->get('worked_hours');
		$activity->reported_at 			= $request->get('reported_at');
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
		$id = \Request::has('id') ? \Request::only('id')['id'] : $id;

        (ActivityReport::destroy($id)) ? \Session::flash('success', [is_array($id) && count($id) > 1 ? 'Las actividades han sido movidas a la papelera correctamente.' : 'La actividad se ha movido a la papelera correctamente.']) : \Session::flash('error', [is_array($id) ? 'Ocurrió un error moviendo las actividades a la papelera.' : 'Ocurrió un problema moviendo la actividad a la papelera.']) ;
		
        return redirect()->route('activityReport.individual');
	}

}
