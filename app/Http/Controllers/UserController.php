<?php
namespace sanoha\Http\Controllers;

use Auth;
use sanoha\Models\Role;
use sanoha\Models\User;
use Illuminate\Http\Request;
use sanoha\Models\CostCenter;
use sanoha\Models\SubCostCenter;
use sanoha\Http\Controllers\Controller;
use sanoha\Http\Requests\UserFormRequest;

class UserController extends Controller
{
    /**
     * The User Model
     */
    private $user;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkPermmisions', ['except' => ['store', 'update']]);
        $this->user = new User;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(UserFormRequest $request)
    {
        $input = $request->all();
        $text = $request->get('find');
        
        $users = $this->user->indexSearch($text);

        return view('users.index', compact('users', 'input'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Role $role, CostCenter $costCenters)
    {
        $roles = $role->lists('display_name', 'id');
        $costCenters = $costCenters->with('subCostCenter')->get();
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees();
        $areas = \sanoha\Models\Areas::orderBy('name')->lists('name', 'id');
        
        return view('users.create', compact('roles', 'costCenters', 'employees', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(UserFormRequest $request, Role $role)
    {
        $user = $this->user->newInstance($request->except('role_id', 'sub_cost_center_id'));
        $user->password = bcrypt($request->get('password'));
        
        // get the roles ids and names
        $role_keys = $role->find($request->get('role_id'))->lists('id');
        $role_names = $role->find($request->get('role_id'))->lists('name');

        // to flash messages
        $success = array();
        $error = array();
        
        // create user
        if ($user->save()) {
            $success[] = 'Usuario creado correctamente.';
            
            // attach roles to user
            $user->attachRoles($role_keys);
            ($user->hasRole($role_names))
                ? $success[] = 'Se ha añadido el rol al usuario correctamente.'
                : $error[] = 'Ocurrió un error añadiendo el rol al usuario.';
    
            // attach cost centers
            if (count($subCostCenters = $request->input('sub_cost_center_id')) > 0) {
                $user->subCostCenters()->sync($subCostCenters)
                    ? $success[] = 'Asignación de centro de costos exitosa.'
                    : $error[] = 'Error asignando centro de costos.' ;
            }
            
            // attach employees
            if (count($employees = $request->get('employee_id')) > 0) {
                $user->employees()->sync($employees)
                    ? $success[] = 'Asignación de empleado(s) exitosa.'
                    : $error[] = 'Falló la asignación de empleado(s).' ;
            }
        } else {
            $error[] = 'Ocurrió un error creando el usuario.';
        }

        // flash notification messages
        \Session::flash('success', $success);
        \Session::flash('error', $error);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = $this->user->findOrFail($id);
        
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id, Role $role, CostCenter $costCenters)
    {
        $user = $this->user->findOrFail($id);
        $roles = $role->lists('display_name', 'id');
        $costCenters = $costCenters->with('subCostCenter')->get();
        $userSubCostCenters = $user->getSubCostCentersId();
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees();
        $areas = \sanoha\Models\Areas::orderBy('name')->lists('name', 'id');

        return view('users.edit', compact('user', 'roles', 'costCenters', 'userSubCostCenters', 'employees', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, UserFormRequest $request, Role $role)
    {
        $user = $this->user->findOrFail($id);
        
        $data = $request->except('role_id', empty($request->get('password')) ? 'password' : null);
        $subCostCenters = empty($request->only('sub_cost_center_id')['sub_cost_center_id']) ? [] : $request->only('sub_cost_center_id')['sub_cost_center_id'];
        
        $data['activated'] = $request->has('activated') ? true : false;
        
        $user->fill($data);
        
        // si se dio una contraseña la actualizo
        if (! empty($request->get('password'))) {
            $user->password = bcrypt($request->get('password'));
        }
        
        $role_names = $role_keys = array();
        
        // to flash messages
        $success = array();
        $error = array();

        // get the roles ids and names if any selected
        if (!empty($request->only('role_id')['role_id'])) {
            $role_keys = $role->find($request->only('role_id')['role_id'])->lists('id');
            $role_names = $role->find($request->only('role_id')['role_id'])->lists('name');
        }

        ($user->save()) ? $success[] = 'Usuario actualizado correctamente.' : $error[] = 'Ocurrió un error actualizando el usuario.';

        // update user roles
        $user->roles()->sync($role_keys);
        
        // attach cost centers
        $user->subCostCenters()->sync($subCostCenters) ? $success[] = 'Actualización de centro de costos exitosa.' : $error[] = 'Error actualizando centro de costos.' ;
        
        if (!empty($role_names)) {
            ($user->hasRole($role_names)) ? $success[] = 'Se ha actualizado el rol del usuario correctamente.' : $error[] = 'Ocurrió un error actualizando el rol al usuario.';
        }

        // attach employees
        if (count($employees = $request->get('employee_id')) > 0) {
            $user->employees()->sync($employees) ? $success[] = 'Asignación de empleado(s) exitosa.' : $error[] = 'Falló la asignación de empleado(s).' ;
        }

        // flash notification messages
        \Session::flash('success', $success);
        \Session::flash('error', $error);

        return redirect()->route('users.index');
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
        
        ($this->user->destroy($id)) ? \Session::flash('success', [is_array($id) && count($id) > 1 ? 'Los usuarios han sido movidos a la papelera correctamente.' : 'El usuario ha sido movido a la papelera.']) : \Session::flash('error', [is_array($id) ? 'Ocurrió un error moviendo los usuarios a la papelera.' : 'Ocurrió un problema moviendo el usuario a la papelera.']) ;

        return redirect()->route('users.index');
    }
}
