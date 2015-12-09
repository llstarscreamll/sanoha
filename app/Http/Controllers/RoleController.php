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
        // el usuario debe haber iniciado sesión
        $this->middleware('auth');
        // control de acceso a los métodos de esta clase
        $this->middleware('checkPermmisions', ['except' => ['store', 'update']]);
        // instancia de modelo de roles
        $this->role = new Role;
        // intancia de modelo de permisos
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
        $categories = $this->permission->categories;
        $permissions = $this->permission->getOrderedPermissions();
        
        return view('roles.create', compact('permissions', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(RoleFormRequest $request)
    {
        $role = $this->role->newInstance($request->all());
        $permissions = $this->permission->whereIn('name', $request->get('permissions'))->get();
        
        // vars to flash messages
        $success = array();
        $error = array();
        
        // si el rol es guardado correctamente, le asocio los permisos especificados
        if ($role->save()) {
            $success[] = 'El rol de usuaro ha sido creado correctamente.';

            $role->perms()->sync($permissions)
                ? $success[] = 'Permisos añadidos al rol correctamente.'
                : $error[] = 'Ocurrió un error añadiendo los permisos.';
        } else {
            $error[] = 'Ocurrió un error creando el rol.';
        }
        
        $request->session()->flash('success', $success);
        $request->session()->flash('error', $error);
        
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

        $permissions = $role->find($id)->perms()->orderBy('name', 'asc')->get(['name', 'display_name'])->toArray();
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
        $role->fill($request->all());
        
        // vars to flash messages
        $success = array();
        $error = array();
        
        // si todo va bien actualizando el rol, le asocio los nuevos permisos
        if ($role->save()) {
            $success[] = 'El rol ha sido actualizado correctamente.';

            $role->perms()->sync($request->get('permissions'))
                ? $success[] = 'Permisos de rol actualizados correctamente.'
                : $error[] = 'Ocurrió un error actualizando los permisos.';
        } else {
            $error[] = 'Ocurrió un error actualizando el rol.';
        }
        
        $request->session()->flash('success', $success);
        $request->session()->flash('error', $error);
        
        return redirect()->route('roles.index');
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
        
        // vars to flash messages
        $success = array();
        $error = array();
        
        $this->role->destroy($id)
            ? $success[] = is_array($id) && count($id) > 1
                ? $request->session()->flash('success', 'Los roles se han movido a la papelera correctamente.')
                : $request->session()->flash('success', 'El rol ha sido movido a la papelera correctamente.')
            : is_array($id) && count($id) > 1
                ? $request->session()->flash('error', 'Error moviendo los roles a la papelera.')
                : $request->session()->flash('error', 'Ocurrió un error moviendo el rol a al papelera.');
        
        return redirect()->route('roles.index');
    }
}
