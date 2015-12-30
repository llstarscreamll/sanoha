<?php
namespace sanoha\Http\Controllers;

use sanoha\Http\Requests;
use sanoha\Models\Employee;
use Illuminate\Http\Request;
use sanoha\Http\Controllers\Controller;
use sanoha\Http\Requests\EmployeeFormRequest;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // el usuario debe haber iniciado sesión
        $this->middleware('auth');
        // control de acceso a los métodos de esta clase
        $this->middleware('checkPermmisions', ['except' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        
        $employees = Employee::with('position', 'subCostCenter', 'subCostCenter.costCenter')
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->get('find').'%')
                    ->orWhere('lastname', 'like', '%'.$request->get('find').'%')
                    ->orWhere('identification_number', 'like', '%'.$request->get('find').'%');
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('employees.index', compact('employees', 'input'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $cost_centers = \sanoha\Models\CostCenter::getOrderListWithSubCostCenters();
        $positions = \sanoha\Models\Position::orderBy('name')->lists('name', 'id')->all();
        
        return view('employees.create', compact('cost_centers', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(EmployeeFormRequest $request)
    {
        $employee                               =   new Employee;
        $employee->sub_cost_center_id           =   $request->get('sub_cost_center_id');
        $employee->position_id                  =   $request->get('position_id');
        $employee->name                         =   $request->get('name');
        $employee->lastname                     =   $request->get('lastname');
        $employee->identification_number        =   $request->get('identification_number');
        $employee->email                        =   empty($request->get('email')) ?: $request->get('email');
        $employee->phone                        =   $request->get('phone', null);
        $employee->authorized_to_drive_vehicles =   $request->get('authorized_to_drive_vehicles', false);
        
        $employee->save()
            ? $request->session()->flash('success', 'Empleado creado correctamente.')
            : $request->session()->flash('error', 'Ocurrió un error creando el empleado.');
        
        return redirect()->route('employee.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        
        $cost_centers = \sanoha\Models\CostCenter::getOrderListWithSubCostCenters();
        $positions = \sanoha\Models\Position::orderBy('name')->lists('name', 'id')->all();
        
        return view('employees.edit', compact('employee', 'cost_centers', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(EmployeeFormRequest $request, $id)
    {
        $employee                               =   Employee::findOrFail($id);
        $employee->name                         =   $request->get('name');
        $employee->lastname                     =   $request->get('lastname');
        $employee->identification_number        =   $request->get('identification_number');
        $employee->email                        =   empty($request->get('email')) ?: $request->get('email');
        $employee->phone                        =   $request->get('phone');
        $employee->sub_cost_center_id           =   $request->get('sub_cost_center_id');
        $employee->position_id                  =   $request->get('position_id');
        $employee->authorized_to_drive_vehicles =   $request->get('authorized_to_drive_vehicles', false);
        
        $employee->save()
            ? $request->session()->flash('success', 'Empleado actualizado correctamente.')
            : $request->session()->flash('error', 'Ocurrió un error actualizado al empleado.');
        
        return redirect()->route('employee.show', $employee->id);
    }
    
    /**
     * Cambio el estado de los empleados de activado a desactivado
     */
    public function status($status, Request $request)
    {
        switch ($status) {
            case 'disabled':
                $action = [
                    'singular'  => 'desactivado',
                    'plural'    =>  'desactivados'
                ];
                break;
            case 'enabled':
            default:
                $action = [
                    'singular'  => 'activado',
                    'plural'    =>  'activados'
                ];
                break;
        }

        if ($request->has('id')) {
            $id = is_array($request->get('id')) ? $request->get('id') : [$request->get('id')];
            
            Employee::whereIn('id', $id)->update(['status' => $status])
                ? $request->session()->flash('success', [is_array($id) && count($id) > 1
                    ? 'Los empleados han sido '.$action['plural'].' correctamente.'
                    : 'El empleado ha sido '.$action['singular'].' correctamente.'])
                : $request->session()->flash('error', [is_array($id)
                    ? 'Los empleados no pudieron ser '.$action['plural'].'.'
                    : 'El empleado no pudo ser '.$action['singular'].'.']) ;
        } else {
            $request->session()->flash('warning', 'Ningún empleado que activar.');
        }

        return redirect()->route('employee.index');
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
        
        Employee::destroy($id)
            ? $request->session()->flash('success', is_array($id) && count($id) > 1
                ? 'Los empleados han sido movidos a la papelera correctamente.'
                : 'El empleado ha sido movido a la papelera correctamente.')
            : $request->session()->flash('error', is_array($id)
                ? 'Ocurrió un error moviendo los empleados a la papelera.'
                : 'Ocurrió un problema moviendo el empleado a la papelera.') ;

        return redirect()->route('employee.index');
    }
}
