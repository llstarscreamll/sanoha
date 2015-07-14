<?php namespace sanoha\Http\Controllers;

use sanoha\Http\Requests;
use sanoha\Http\Controllers\Controller;
use sanoha\Http\Requests\ActivityReportFormRequest;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityReportController extends Controller {
	
	/**
	 * El centro de costos con el que se está trabajando
	 * 
	 * @var	string
	 */ 
	private $costCenter_id;
	
	/**
	 * 
	 */ 
	public function __construct()
	{
		// me aseguro que se haya elejido un centro de costos y que el usuario tenga acceso
		// a ese centro
		$this->middleware('checkCostCenter', ['except' => ['selectCostCenterView', 'costCenterActivities']]);
		
		// control de acceso a los métodos de esta clase
		$this->middleware('checkPermmisions', ['except' => ['store','update','selectCostCenterView','costCenterActivities']]);
		
		$this->costCenter_id = \Session::get('currentCostCenterActivities');
	}

	/**
	 * Vista para elegir el centro de costo con el que se quiere trabajar en caso
	 * de que la variable de session "currentCostCenterActivities" no esté seteada
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
	public function costCenterActivities($costCenter)
	{
		\Session::put('currentCostCenterActivities', $costCenter);
		return redirect()->route('activityReport.index');
	}

	/**
	 * Muestro un informe con las actividades reportadas, en donde se podrá consultar
	 * filtrar por fechas, por nombres, apellidos, número de documento, etc... El
	 * reporte predeterminado muestra las actividades registradas del dia
	 * anterior.
	 * 
	 * @param	sanoha\Http\Requests\ActivityReportFormRequest	$requests
	 * @return	\Illuminate\Http\Response	
	 */
	public function index(ActivityReportFormRequest $request)
	{
		$search_input = $request->all();
        
        $start = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
        $end = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
        
        $start->subDays(1);
        $end->subDays(1);
        
        $parameters['employee'] = !empty($request->get('find')) ? $request->get('find') : null;
        
		$parameters['from'] = !empty($request->get('from')) ? $request->get('from')  : $start->toDateString();
		$parameters['from'] .= ' 00:00:00';
		
		$parameters['to'] = !empty($request->get('to')) ? $request->get('to') : $end->toDateString();
		$parameters['to'] .= ' 23:59:59';
		
		$parameters['costCenter_id'] = $this->costCenter_id;
		$parameters['costCenter_name'] = \sanoha\Models\CostCenter::findOrFail($this->costCenter_id)->name;

		$activities = \sanoha\Models\ActivityReport::getActivities($parameters);
			
		$orderedActivities = \sanoha\Models\ActivityReport::sortActivities($activities);
		
		$miningActivities = \sanoha\Models\MiningActivity::orderBy('name', 'ASC')->get();

		return view('activityReports.index', compact('orderedActivities', 'miningActivities', 'parameters', 'search_input'));
	}

	/**
	 * Muestra el formulario para registrar una actividad minera de un trabajador.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		// parametros de búsqueda de activiades del empleado
		
        $start = Carbon::createFromFormat('Y-m-d', date('Y-m-d')); // busqueda desde el día de hoy
        $end = Carbon::createFromFormat('Y-m-d', date('Y-m-d')); // hasta el día de hoy
        
        $parameters = [];
        
        $parameters['employee_id'] = $request->has('employee_id') ? $request->get('employee_id') : null;
		
		$employee = empty($parameters['employee_id']) ? null : \sanoha\Models\Employee::find($parameters['employee_id']);
        
		$parameters['from'] = !empty($request->get('from')) ? $request->get('from')  : $start->toDateString();
		$parameters['from'] .= ' 00:00:00';
		
		$parameters['to'] = !empty($request->get('to')) ? $request->get('to') : $end->toDateString();
		$parameters['to'] .= ' 23:59:59';
		
		$parameters['costCenter_id'] = $this->costCenter_id;
		$parameters['costCenter_name'] = \sanoha\Models\CostCenter::findOrFail($this->costCenter_id)->name;
		
		// lista de todos los empleados del proyecto
		$employees = \sanoha\Models\Employee::where('cost_center_id', '=', $this->costCenter_id)
			->orderBy('name')
			->get()
			->lists('fullname', 'id');
		
		// las posibles actividades a registrar
		$miningActivities = \sanoha\Models\MiningActivity::orderBy('name')
			->get();
		
		// las actividades del empleado
		$employee_activities = \sanoha\Models\ActivityReport::getActivities($parameters);
			
		//dd($employee_activities);
		$employee_activities = \sanoha\Models\ActivityReport::sortActivities($employee_activities);
		
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

		$activity 						=	new \sanoha\Models\ActivityReport;
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
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
