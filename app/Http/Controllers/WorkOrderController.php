<?php
namespace sanoha\Http\Controllers;

use \sanoha\Http\Requests;
use Illuminate\Http\Request;
use \sanoha\Models\WorkOrder;
use \sanoha\Models\ExternalAccompanist;
use \sanoha\Http\Controllers\Controller;
use \sanoha\Http\Requests\WorkOrderFormRequest;

class WorkOrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkPermmisions', ['except' => [
            'store',
            'update',
            'mainReportStore',
            'mainReportUpdate',
            'internalAccompanistReportStore',
            'vehicleMovementStore'
        ]]);
    }

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
        $vehicle_responsibles = \sanoha\Models\Employee::where('authorized_to_drive_vehicles', true)->get()->lists('fullname', 'id');
        $vehicles = \sanoha\Models\Vehicle::all()->lists('plate', 'id');
        
        return view('workOrders.create', compact('employees', 'vehicles', 'vehicle_responsibles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(WorkOrderFormRequest $request)
    {
        // creo la orden de trabajo
        $workOrder                          =   new WorkOrder;
        $workOrder->authorized_by           =   \Auth::getUser()->id;
        $workOrder->vehicle_id              =   $request->get('vehicle_id');
        $workOrder->vehicle_responsable     =   $request->get('vehicle_responsable');
        $workOrder->destination             =   $request->get('destination');
        $workOrder->work_description        =   $request->get('work_description');
        
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
        $workOrder = WorkOrder::with([
            'employee',
            'vehicleMovements',
            'workOrderReports',
            'workOrderReports.reportedBy',
            'internalAccompanists.position',
            'internalAccompanists'  =>  function($q){ $q->orderBy('lastname'); },
            'workOrderReports'      =>  function($q){ $q->orderBy('updated_at'); },
            'externalAccompanists'  =>  function($q){ $q->orderBy('fullname'); }
        ])->where('id', $id)->firstOrFail();
        
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
        $vehicle_responsibles = \sanoha\Models\Employee::where('authorized_to_drive_vehicles', true)->get()->lists('fullname', 'id');
        $vehicles = \sanoha\Models\Vehicle::all()->lists('plate', 'id');
        
        return view('workOrders.edit', compact('workOrder', 'employees', 'vehicles', 'vehicle_responsibles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, WorkOrderFormRequest $request)
    {
        $workOrder                          =   WorkOrder::findOrFail($id);
        $workOrder->authorized_by           =   \Auth::getUser()->id;
        $workOrder->vehicle_id              =   $request->get('vehicle_id');
        $workOrder->vehicle_responsable     =   $request->get('vehicle_responsable');
        $workOrder->destination             =   $request->get('destination');
        $workOrder->work_description        =   $request->get('work_description');
        
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
            'work_order_report' =>  \Purifier::clean($request->get('work_order_report')),
            'reported_by'       =>  \Auth::getUser()->id
            ]);
            
        $workOrder->workOrderReports()->save($workOrderReport)
            ? \Session::flash('success', 'Reporte guardado con éxito')
            : \Session::flash('error', 'Ocurrió un problema guardando el reporte');
        
        return redirect()->route('workOrder.show', $workOrder->id);

    }
    
    /**
     * Muestra el formulario para la edición de un reporte de la orden de trabajo
     * 
     * @param int $work_order_id
     * @param int $main_report_id
     */
    public function mainReportEdit($work_order_id, $main_report_id)
    {
        $workOrder = \sanoha\Models\WorkOrder::findOrFail($work_order_id);
        $mainReport = \sanoha\Models\WorkOrderReport::findOrFail($main_report_id);
        
        return view('workOrders.editMainReport', compact('workOrder', 'mainReport'));
    }
    
    /**
     * Actualiza la información del reporte principal de la orden de trabajo
     * 
     * @param int $work_order_id
     * @param int $main_report_id
     */
    public function mainReportUpdate($work_order_id, $main_report_id, WorkOrderFormRequest $request)
    {
        $workOrder = \sanoha\Models\WorkOrder::findOrFail($work_order_id);
        $mainReport = \sanoha\Models\WorkOrderReport::findOrFail($main_report_id);

        $mainReport->work_order_report = $request->get('work_order_report');
        $mainReport->reported_by = \Auth::getUser()->id;
        
        $mainReport->save()
            ? \Session::flash('success', 'Reporte principal actualizado correctamente.')
            : \Session::flash('error', 'Ocurrió un problema actualizado el reporte');
            
        return redirect()->route('workOrder.show', $workOrder->id);
    }
    
    /**
     * Elimino (softdelete) el reporte principal de la orden de trabajo
     * 
     * @param int $report_id
     */
    public function mainReportDestroy($report_id)
    {
        \sanoha\Models\WorkOrderReport::destroy($report_id)
            ? \Session::flash('success', 'El reporte principal ha sido movido a la papelera correctamente.')
            : \Session::flash('error', 'Ocurrió un problema moviendo a la papelera el reporte principal.');
        
        return redirect()->back();
    }
    
    /**
     * Muestra el formulario donde el acompañante interno reporta las actividades
     * realizdas en la orden de trabajo
     * 
     * @param int $work_order_id
     * @param int $main_report_id
     */
    public function internalAccompanistReportForm($work_order_id, $employee_id)
    {
        $workOrder = \sanoha\Models\WorkOrder::findOrFail($work_order_id);
        $employee = \sanoha\Models\Employee::findOrFail($employee_id);
        
        return view('workOrders.internalAccompanistReportForm', compact('workOrder', 'employee'));
    }
    
    /**
     * Muestra el formulario donde el acompañante interno editará el reporte de
     * las actividades realizdas en la orden de trabajo
     * 
     * @param int $work_order_id
     * @param int $main_report_id
     */
    public function internalAccompanistReportEditForm($work_order_id, $employee_id)
    {
        $workOrder = \sanoha\Models\WorkOrder::with(['internalAccompanists' => function($q) use($employee_id){
                $q->where('employee_id', $employee_id);
            }])
            ->findOrFail($work_order_id);
        $employee = \sanoha\Models\Employee::findOrFail($employee_id);
        
        return view('workOrders.internalAccompanistEditReportForm', compact('workOrder', 'employee'));
    }
    
    /**
     * Guarda en la base de datos el reporte del acompañante interno de la orden
     * de trabajo
     * 
     * @param int $work_order_id
     * @param int $main_report_id
     */
    public function internalAccompanistReportStore($work_order_id, $employee_id, WorkOrderFormRequest $request)
    {
        $workOrder = \sanoha\Models\WorkOrder::findOrFail($work_order_id);
        $employee = \sanoha\Models\Employee::findOrFail($employee_id);

        $workOrder->internalAccompanists()->sync([$employee->id => [
            'work_report' => $request->get('work_order_report'),
            'reported_by' => \Auth::getUser()->id,
            'reported_at' => date('Y-m-d H:i:s')
        ]], false);
        
        \Session::flash('success', 'Se ha guardado el reporte del acompañante interno.');
        
        return redirect()->route('workOrder.show', $workOrder->id);
    }
    
    /**
     * Borra el reporte de un acompañante interno de la orden de trabajo
     * 
     * @param int $work_order_id
     * @param int $main_report_id
     */
    public function internalAccompanistReportDelete($work_order_id, $employee_id)
    {
        $workOrder = \sanoha\Models\WorkOrder::findOrFail($work_order_id);
        $employee = \sanoha\Models\Employee::findOrFail($employee_id);

        $workOrder->internalAccompanists()->sync([$employee->id => [
            'work_report' => null,
            'reported_by' => null,
            'reported_at' => null
        ]], false);
        
        \Session::flash('success', 'El reporte del acompañante interno ha sido borrado correctamente.');
        
        return redirect()->route('workOrder.show', $workOrder->id);
    }

    /**
     * Muestra el formulario para el registro de entradas o salidas del vehículo de la orden
     */
    public function vehicleMovementForm($work_order_id, $action)
    {
        $workOrder = WorkOrder::findOrFail($work_order_id);

        return view('workOrders.vehicleMovementForm', compact('workOrder', 'action'));
    }

    /**
     * Registra en DB las condiciones en que salió o entró el vehículo de la orden de trabajo
     */
    public function vehicleMovementStore($work_order_id, $action, WorkOrderFormRequest $request)
    {
        // para el mensaje del usuario
        $msgAction = ($action == 'exit') ? 'Salida' : 'Entrada';

        $workOrder = WorkOrder::findOrFail($work_order_id);
        $vehicleMovement = new \sanoha\Models\VehicleMovement;
        $vehicleMovement->action                        =  $action;
        $vehicleMovement->registered_by                 =  \Auth::getUser()->id;
        $vehicleMovement->mileage                       =  $request->get('mileage');
        $vehicleMovement->fuel_level                    =  $request->get('fuel_level');
        $vehicleMovement->internal_cleanliness          =  $request->get('internal_cleanliness');
        $vehicleMovement->external_cleanliness          =  $request->get('external_cleanliness');
        $vehicleMovement->paint_condition               =  $request->get('paint_condition');
        $vehicleMovement->bodywork_condition            =  $request->get('bodywork_condition');
        $vehicleMovement->right_front_wheel_condition   =  $request->get('right_front_wheel_condition');
        $vehicleMovement->left_front_wheel_condition    =  $request->get('left_front_wheel_condition');
        $vehicleMovement->rear_right_wheel_condition    =  $request->get('rear_right_wheel_condition');
        $vehicleMovement->rear_left_wheel_condition     =  $request->get('rear_left_wheel_condition');
        $vehicleMovement->comment                       =  $request->get('comment');

        $workOrder->vehicleMovements()->save($vehicleMovement)
            ? \Session::flash('success', $msgAction.' registrada correctamente.')
            : \Session::flash('error', 'Ocurró un problema registrando la '.$msgAction.'.');

        return redirect()->route('workOrder.index');
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
        
        (\sanoha\Models\WorkOrder::destroy($id))
            ? \Session::flash('success', [is_array($id) && count($id) > 1
                ? 'Las ordenes de trabajo han sido movidas a la papelera correctamente.'
                : 'La orden de trabajo ha sido movida a la papelera correctamente.'])
            : \Session::flash('error', [is_array($id)
                ? 'Ocurrió un error moviendo las ordenes de trabajo a la papelera.'
                : 'Ocurrió un problema moviendo las ordenes de trabajo a la papelera.']) ;

        return redirect()->route('workOrder.index');
    }

}
