<?php
namespace sanoha\Http\Controllers;

use sanoha\Http\Requests;
use sanoha\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \Carbon\Carbon;

use \sanoha\Models\Novelty;
use \sanoha\Models\Employee;
use \sanoha\Models\NoveltyReport;
use \sanoha\Models\SubCostCenter;
use sanoha\Http\Requests\NovletyReportFormRequest;

class NoveltyReportController extends Controller
{
	/** 
	 * El centro de costos con el que se está trabajando
	 * 
	 * @var	string
	 */
	private $cost_center_id;
	
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
	 * Al igual que en el controlador de reporte de actividades mineras...
	 * Vista para elegir el centro de costo con el que se quiere trabajar en caso
	 * de que la variable de session "current_cost_center_id" no esté seteada
	 * por algún motivo.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function selectCostCenterView()
	{
		return view('noveltyReports.selectCostCenter');
	}
	
		
	/**
	 * Al igual que en el controlador de reporte de actividades mineras...
	 * Guarda en una variable de sesión el centro de costo con el que va a trabajar
	 * el controlador, el centro de costo es seleccionado a través de la barra de
	 * navegación o la vista generada en selectCostCenterView().
	 * 
	 * @param	string		El id del centro de costos de la DB
	 * @return	redirect	Redirecciona al vista index de reporte de actividades
	 */ 	
	public function setCostCenter($cost_center)
	{
		$cost_center = \sanoha\Models\CostCenter::findOrFail($cost_center);
		
		\Session::put('current_cost_center_name', $cost_center->name);
		\Session::put('current_cost_center_id', $cost_center->id);
		
		return redirect()->route('noveltyReport.index');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(NovletyReportFormRequest $request)
	{
		$search_input = $request->all();
		$novelties = NoveltyReport::individualNovelties($parameters = NoveltyReport::configureParameters($request, $this->cost_center_id));
		
		return view('noveltyReports.index', compact('novelties', 'search_input', 'parameters'));
	}
	
	/**
	 * La vista en calendario de las novedades reportadas
	 */
	public function calendar(NovletyReportFormRequest $request)
	{
		$search_input = $request->all();
		$json_novelties = \sanoha\Models\NoveltyReport::getCalendarNovelties($parameters = NoveltyReport::configureParameters($request, $this->cost_center_id, ['start' => \Carbon\Carbon::now()->startOfMonth()->startOfDay()]));

		return view('noveltyReports.calendar', compact('json_novelties', 'search_input', 'parameters'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$novelties = Novelty::all()->lists('name', 'id');
		$subCostCenterEmployees = \sanoha\Models\SubCostCenter::where('cost_center_id', $this->cost_center_id)->with('employees')->get();
		// obtengo la lista de empleados relacinados al centro de costo y doy la opción de incluir
        // al empleado que hizo la labor minera si es que no se encuentra dentro de los empleados del
        // centro de costo
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees($this->cost_center_id, null, null, [
            'employees' => [
                'position_id' => NoveltyReport::getPositionsToInclude()
            ]
        ]);
		$employee_id = \Request::get('employee_id', old('employee_id'));

		return view('noveltyReports.create', compact('employees', 'novelties', 'employee_id'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(NovletyReportFormRequest $request)
	{
		$request = $request->all();
		$sub_cost_center_id = Employee::findOrFail($request['employee_id'])->sub_cost_center_id;
		
		$novelty						= new NoveltyReport;
		$novelty->sub_cost_center_id	= $sub_cost_center_id;
		$novelty->employee_id			= $request['employee_id'];
		$novelty->novelty_id			= $request['novelty_id'];
		$novelty->comment				= $request['comment'] or null;
		$novelty->reported_at			= $request['reported_at'];
		
		$novelty->save()
			? \Session::flash('success', 'Novedad reportada exitosamente.')
			: \Session::flash('error', 'Ocurrió un error reportando la novedad.');
		
		return redirect(route('noveltyReport.create'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$novelty = NoveltyReport::findOrFail($id);
		
		return view('noveltyReports.show', compact('novelty'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$novelty = NoveltyReport::findOrFail($id);
		
		$novelties = Novelty::all()->lists('name', 'id');
		$subCostCenterEmployees = \sanoha\Models\SubCostCenter::where('cost_center_id', $this->cost_center_id)->with('employees')->get();
		// obtengo la lista de empleados relacinados al centro de costo y doy la opción de incluir
        // al empleado que hizo la labor minera si es que no se encuentra dentro de los empleados del
        // centro de costo
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees($this->cost_center_id, $novelty->employee_id, null, [
            'employees' => [
                'position_id' => NoveltyReport::getPositionsToInclude()
            ]
        ]);

		$employee_id = null;
		
		return view('noveltyReports.edit', compact('novelty', 'novelties', 'employees', 'employee_id'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, NovletyReportFormRequest $request)
	{
		$request = $request->all();
		
		$sub_cost_center_id = Employee::findOrFail($request['employee_id'])->sub_cost_center_id;
		
		$novelty = NoveltyReport::findOrFail($id);
		$novelty->sub_cost_center_id	= $sub_cost_center_id;
		$novelty->employee_id			= $request['employee_id'];
		$novelty->novelty_id			= $request['novelty_id'];
		$novelty->comment				= $request['comment'] or null;
		$novelty->reported_at			= $request['reported_at'];
		
		$novelty->save()
			? \Session::flash('success', 'Novedad actualizada exitosamente.')
			: \Session::flash('error', 'Ocurrió un error actualizando la novedad.');
		
		return redirect()->route('noveltyReport.show', $id);
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
        
        (NoveltyReport::destroy($id))
        	? \Session::flash('success', [is_array($id) && count($id) > 1
        		? 'Las novedades han sido movidos a la papelera correctamente.'
        		: 'La novedad ha sido movida a la papelera correctamente.'])
        	: \Session::flash('error', [is_array($id)
        		? 'Ocurrió un error moviendo las novedades a la papelera.'
        		: 'Ocurrió un problema moviendo la novedades a la papelera.']) ;

        return redirect()->route('noveltyReport.index');
	}

}
