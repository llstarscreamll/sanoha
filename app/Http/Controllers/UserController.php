<?php namespace sanoha\Http\Controllers;

use sanoha\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use sanoha\Http\Requests\UserFormRequest;
use sanoha\Models\Role;
use sanoha\Models\User;
use sanoha\Models\CostCenter;
use sanoha\Models\SubCostCenter;

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
		//$this->middleware('auth');
		$this->middleware('checkPermmisions', ['except' => ['store','update']]);
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
        
        return view('users.create', compact('roles', 'costCenters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(UserFormRequest $request, Role $role)
    {
        $user = $this->user->newInstance($request->except('role_id', 'subCostCenter_id'));

        // get the roles ids and names
        $role_keys = $role->find($request->only('role_id')['role_id'])->lists('id');
        $role_names = $role->find($request->only('role_id')['role_id'])->lists('name');

        // to flash messages
        $success = array();
        $error = array();

        // create user
        ($user->save()) ? $success[] = 'Usuario creado correctamente.' : $error[] = 'Ocurrió un error creando el usuario.';
        
        // attach roles to user
        $user->attachRoles($role_keys);
        ($user->hasRole($role_names)) ? $success[] = 'Se ha añadido el rol al usuario correctamente.' : $error[] = 'Ocurrió un error añadiendo el rol al usuario.';

        // attach cost centers
        $subCostCenters = $request->input('subCostCenter_id');
        //dd($subCostCenter);
        ( $user->subCostCenters()->sync($subCostCenters) ) ? $success[] = 'Asignación de centro de costos exitosa.' : $error[] = 'Error asignando centro de costos.' ;

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

        return view('users.edit', compact('user', 'roles', 'costCenters', 'userSubCostCenters'));
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
        
        $data = $request->except('role_id', empty($request->only('password')['password']) ? 'password' : null);
        $subCostCenters = empty($request->only('subCostCenter_id')['subCostCenter_id']) ? [] : $request->only('subCostCenter_id')['subCostCenter_id'];
        
        $data['activated'] = $request->has('activated') ? true : false;
        
        $user->fill($data);
        
        $role_names = $role_keys = array();
        
        // to flash messages
        $success = array();
        $error = array();

        // get the roles ids and names if any selected
        if(!empty($request->only('role_id')['role_id'])){
            $role_keys = $role->find($request->only('role_id')['role_id'])->lists('id');
            $role_names = $role->find($request->only('role_id')['role_id'])->lists('name');
        }

        ($user->save()) ? $success[] = 'Usuario actualizado correctamente.' : $error[] = 'Ocurrió un error actualizando el usuario.';

        // update user roles
        $user->roles()->sync($role_keys);
        
        // attach cos centers
        //dd($subCostCenters);
        $user->subCostCenters()->sync($subCostCenters) ? $success[] = 'Actualización de centro de costos exitosa.' : $error[] = 'Error actualizando centro de costos.' ;
        
        if(!empty($role_names))
            ($user->hasRole($role_names)) ? $success[] = 'Se ha actualizado el rol del usuario correctamente.' : $error[] = 'Ocurrió un error actualizando el rol al usuario.';

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
