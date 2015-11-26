<?php
namespace sanoha\Http\Controllers;

use \sanoha\Models\Role;
use Illuminate\Http\Request;
use \sanoha\Models\Permission;
use sanoha\Http\Controllers\Controller;
use sanoha\Http\Requests\RoleFormRequest;

class RoleController extends Controller
{
    /**
     * The Role model
     * 
     */
    private $role;
    
    /**
     * The Permission model
     * 
     */
    private $permission;
     
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermmisions', ['except' => ['store', 'update']]);
        
        $this->role = new Role;
        $this->permission = new Permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = $this->role->orderBy('updated_at', 'des')->paginate(15);
        
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $permissions = $this->permission;
        $categories = $permissions->categories;
        $permissions = $permissions->getOrderedPermissions();
        
        return view('roles.create', compact('permissions', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(RoleFormRequest $request)
    {
        $role = $this->role->newInstance($request->except('permissions'));
        $permissions = $this->permission->whereIn('name', $request->only('permissions')['permissions'])->get();
        
        // vars to flash messages
        $success = array();
        $error = array();
        
        $role->save() ? $success[] = 'El rol de usuaro ha sido creado correctamente.' : $error[] = 'Ocurrió un error creando el rol.';
        $role->perms()->sync($permissions) ? $success[] = 'Permisos añadidos al rol correctamente.' : $error[] = 'Ocurrió un error añadiendo los permisos.';
        
        \Session::flash('success', $success);
        \Session::flash('error', $error);
        
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $role = $this->role->findOrFail($id);
        $permissions = $role->find($id)->perms()->select('name', 'display_name')->orderBy('name', 'asc')->get()->toArray();
        $permissions = !empty($permissions) ?  $this->permission->getOrderedPermissions($permissions) : [];

        $categories = $this->permission->categories;

        return view('roles.show', compact('role', 'permissions', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $role = $this->role->findOrFail($id);
        $permissions = $this->permission->getOrderedPermissions();
        $rolePermissions = $role->find($id)->perms()->orderBy('name', 'asc')->lists('name');

        $categories = $this->permission->categories;
        
        return view('roles.edit', compact('role', 'permissions', 'categories', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, RoleFormRequest $request)
    {
        $role = $this->role->findOrFail($id);
        $role->fill($request->except('permissions'));
        $permissions = $this->permission->whereIn('name', $request->only('permissions')['permissions'])->get();
        
        // vars to flash messages
        $success = array();
        $error = array();
        
        $role->save() ? $success[] = 'El rol ha sido actualizado correctamente.' : $error[] = 'Ocurrió un error actualizando el rol.';
        $role->perms()->sync($permissions) ? $success[] = 'Permisos de rol actualizados correctamente.' : $error[] = 'Ocurrió un error actualizando los permisos.';
        
        \Session::flash('success', $success);
        \Session::flash('error', $error);
        
        return redirect()->route('roles.index');
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
        
        // vars to flash messages
        $success = array();
        $error = array();
        
        $this->role->destroy($id) ? $success[] = is_array($id) && count($id) > 1 ? 'Los roles se han movido a la papelera correctamente.' : 'El rol ha sido movido a la papelera correctamente.' : 'Ocurrió un error moviendo el rol a al papelera.' ;
        
        \Session::flash('success', $success);
        \Session::flash('error', $error);
        
        return redirect()->route('roles.index');
    }
}
