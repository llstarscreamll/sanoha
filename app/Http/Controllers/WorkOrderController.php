<?php namespace sanoha\Http\Controllers;

use sanoha\Http\Requests;
use sanoha\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \sanoha\Models\WorkOrder;
use \sanoha\Models\ExternalAccompanist;

class WorkOrderController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$workOrders = WorkOrder::orderBy('created_at')->paginate(15);

		return view('workOrders.index', compact('workOrders'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$employees = \sanoha\Models\SubCostCenter::getRelatedEmployees();
		$vehicles = \sanoha\Models\Vehicle::all()->lists('plate', 'id');
		
		return view('workOrders.create', compact('employees', 'vehicles'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// creo la orden de trabajo
		$workOrder							=	new WorkOrder;
		$workOrder->authorized_by			=	\Auth::getUser()->id;
		$workOrder->vehicle_id				=	$request->get('vehicle_id');
		$workOrder->vehicle_responsable		=	$request->get('vehicle_responsable');
		$workOrder->destination				=	$request->get('destination');
		$workOrder->work_description		=	$request->get('work_description');
		
		// donde guardo los mensaje de la operación para el usuario
		$msg_error = [];
		$msg_success = [];
		// array de los modelos de acompañantes externos de orden de trabajo
		$external_accompanists = [];
		
		if ($workOrder->save()){
			$msg_success[] = 'Orden de trabajo creada correctamente.';
			
			// una vez creada la orden de trabajo, asocio los acompañantes (empleados) a la orden,
			// si es que se especificaron
			if(!empty($request->get('internal_accompanists'))){
				$workOrder->internalAccompanists()->sync($request->get('internal_accompanists'));
				$msg_success[] = 'Acompañantes internos asociados correctamente.';
			}
			
			// asocio los los acompañantes externos a la orden de trabajo, si es que se ha especificado alguno
			if(!empty($request->get('external_accompanists'))){
				foreach ($request->get('external_accompanists') as $key => $value) {
					$external_accompanists[] = new ExternalAccompanist(['fullname' => $value]);
				}

				$workOrder->externalAccompanists()->saveMany($external_accompanists)
					? $msg_success[] = 'Acompañantes externos asociados correctamente.'
					: $msg_error[] = 'Error asociando los acompañates externos.';
			}
			
		}else{
			$msg_error[] = 'Error creaendo la orden de trabajo.';
		}
		
		\Session::flash('success', $msg_success);
		\Session::flash('error', $msg_error);
		
		return redirect()->route('workOrder.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$workOrder = WorkOrder::findOrFail($id);
		
		return view('workOrders.show', compact('workOrder'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$workOrder = WorkOrder::findOrFail($id);
		$employees = \sanoha\Models\SubCostCenter::getRelatedEmployees();
		$vehicles = \sanoha\Models\Vehicle::all()->lists('plate', 'id');
		
		return view('workOrders.edit', compact('workOrder', 'employees', 'vehicles'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$workOrder							=	WorkOrder::findOrFail($id);
		$workOrder->authorized_by			=	\Auth::getUser()->id;
		$workOrder->vehicle_id				=	$request->get('vehicle_id');
		$workOrder->vehicle_responsable		=	$request->get('vehicle_responsable');
		$workOrder->destination				=	$request->get('destination');
		$workOrder->work_description		=	$request->get('work_description');
		
		// donde guardo los mensaje de la operación para el usuario
		$msg_error = [];
		$msg_success = [];
		// array de los modelos de acompañantes externos de orden de trabajo
		$external_accompanists = [];
		
		if ($workOrder->save()){
			$msg_success[] = 'La orden ha sido actualizada correctamente.';
			
			// una vez creada la orden de trabajo, asocio los acompañantes (empleados) a la orden,
			// si es que se especificaron
			if(!empty($request->get('internal_accompanists'))){
				$workOrder->internalAccompanists()->sync($request->get('internal_accompanists'), false);
				$msg_success[] = 'Acompañantes internos actualizados correctamente.';
			}
			
			// asocio los los acompañantes externos a la orden de trabajo, si es que se ha especificado alguno
			if(!empty($request->get('external_accompanists'))){
				foreach ($request->get('external_accompanists') as $key => $value) {
					$external_accompanists[] = new ExternalAccompanist(['fullname' => $value]);
				}
				
				$workOrder->externalAccompanists()->delete() && $workOrder->externalAccompanists()->saveMany($external_accompanists)
					? $msg_success[] = 'Acompañantes externos actualizados correctamente.'
					: $msg_error[] = 'Error actualizados los acompañates externos.';
			}
			
		}else{
			$msg_error[] = 'Error creaendo la orden de trabajo.';
		}
		
		\Session::flash('success', $msg_success);
		\Session::flash('error', $msg_error);
		
		return redirect()->route('workOrder.show', $workOrder->id);
	}
	
	/**
	 * El formulario para crear el reporte principal de la orden de trabajo
	 */
	public function mainReportForm($id)
	{
		$workOrder = WorkOrder::findOrFail($id);
		return view('workOrders.mainReportForm', compact('workOrder'));
	}
	
	/**
	 * Guardo el reporte de la orden de trabajo
	 */
	public function mainReportStore($id, Request $request)
	{
		$workOrder = WorkOrder::findOrFail($id);

		$workOrderReport = new \sanoha\Models\WorkOrderReport([
			'work_order_report'	=>	\Purifier::clean($request->get('work_order_report')),
			'reported_by'		=>	\Auth::getUser()->id
			]);
			
		$workOrder->workOrderReports()->save($workOrderReport)
			? \Session::flash('success', 'Reporte guardado con éxito')
			: \Session::flash('error', 'Ocurrió un problema guardando el reporte');
		
		return redirect()->route('workOrder.show', $workOrder->id);

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
