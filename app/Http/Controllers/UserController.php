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
        // el usuario debe haber iniciado sesión
        $this->middleware('auth');
        // control de acceso a los métodos de esta clase
        $this->middleware('checkPermmisions', ['except' => ['store', 'update']]);
        // instancia de modelo usuario
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
        $users = $this->user->indexSearch($request->get('find'));

        return view('users.index', compact('users', 'input'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::lists('display_name', 'id');
        $costCenters = CostCenter::with('subCostCenter')->get();
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees();
        $areas = \sanoha\Models\Areas::orderBy('name')->lists('name', 'id')->all();
        
        return view('users.create', compact('roles', 'costCenters', 'employees', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(UserFormRequest $request)
    {
        $user               = $this->user->newInstance();
        $user->area_id      = empty($request->get('area_id')) ? null : $request->get('area_id');
        $user->name         = $request->get('name');
        $user->lastname     = $request->get('lastname');
        $user->email        = $request->get('email');
        $user->password     = bcrypt($request->get('password'));
        $user->activated    = $request->get('activated', false);
        $user->area_chief   = $request->get('area_chief', false);

        // to flash messages
        $success = array();
        $error = array();
        
        // create user
        if ($user->save()) {
            $success[] = 'Usuario creado correctamente.';
            
            // attach roles to user
            if (count($request->get('role_id')) > 0){
                $user->roles()->sync($request->get('role_id'))
                    ? $success[] = 'Se ha añadido el rol al usuario correctamente.'
                    : $error[] = 'Ocurrió un error añadiendo el rol al usuario.';
            }
    
            // attach cost centers
            if (count($request->get('sub_cost_center_id')) > 0){
                $user->subCostCenters()->sync($request->get('sub_cost_center_id'))
                    ? $success[] = 'Asignación de centro de costos exitosa.'
                    : $error[] = 'Error asignando centro de costos.';
            }
            
            // attach employees
            if (count($request->get('employee_id')) > 0){
                $user->employees()->sync($request->get('employee_id'))
                    ? $success[] = 'Asignación de empleado(s) exitosa.'
                    : $error[] = 'Falló la asignación de empleado(s).';
            }
        } else {
            $error[] = 'Ocurrió un error creando el usuario.';
        }

        // flash notification messages
        $request->session()->flash('success', $success);
        $request->session()->flash('error', $error);

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
    public function edit($id)
    {
        $user = $this->user->findOrFail($id);

        $roles = Role::lists('display_name', 'id')->all();
        $costCenters = CostCenter::with('subCostCenter')->get();
        $userSubCostCenters = $user->getSubCostCentersId();
        $employees = \sanoha\Models\SubCostCenter::getRelatedEmployees();
        $areas = \sanoha\Models\Areas::orderBy('name')->lists('name', 'id')->all();

        return view('users.edit', compact('user', 'roles', 'costCenters', 'userSubCostCenters', 'employees', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, UserFormRequest $request)
    {
        $user = $this->user->findOrFail($id);

        // actualizo la info del usuario
        $user->area_id      = empty($request->get('area_id')) ? null : $request->get('area_id');
        $user->name         = $request->get('name');
        $user->lastname     = $request->get('lastname');
        $user->email        = $request->get('email');
        $user->password     = empty($request->get('password')) ? $user->password : bcrypt($request->get('password'));
        $user->activated    = $request->get('activated', false);
        $user->area_chief   = $request->get('area_chief', false);
        
        $success = array();
        $error = array();

        // si todo va bien, guardando el usuario y entonces le asocio la demas información
        if ($user->save()) {
            $success[] = 'Usuario actualizado correctamente.';

            // attach cost centers
            $user->subCostCenters()->sync($request->get('sub_cost_center_id', []))
                ? $success[] = 'Actualización de centro de costos exitosa.'
                : $error[] = 'Error actualizando centro de costos.' ;

            // update user roles
            $user->roles()->sync($request->get('role_id', []))
                ? $success[] = 'Se ha actualizado el rol del usuario correctamente.'
                : $error[] = 'Ocurrió un error actualizando el rol al usuario.';

            // attach employees
            $user->employees()->sync($request->get('employee_id', []))
                ? $success[] = 'Asignación de empleado(s) exitosa.'
                : $error[] = 'Falló la asignación de empleado(s).' ;
        } else {
            $error[] = 'Ocurrió un error actualizando el usuario.';
        }

        // flash notification messages
        $request->session()->flash('success', $success);
        $request->session()->flash('error', $error);

        return redirect()->route('users.index');
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
        
        $this->user->destroy($id)
            ? $request->session()->flash('success', is_array($id) && count($id) > 1
                ? 'Los usuarios han sido movidos a la papelera correctamente.'
                : 'El usuario ha sido movido a la papelera.')
            : $request->session()->flash('error', is_array($id)
                ? 'Ocurrió un error moviendo los usuarios a la papelera.'
                : 'Ocurrió un problema moviendo el usuario a la papelera.') ;

        return redirect()->route('users.index');
    }
}
