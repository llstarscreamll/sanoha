<?php
namespace sanoha\Http\Controllers;

use \sanoha\Models\Role;
use Illuminate\Http\Request;
use \sanoha\Models\Permission;
use Spatie\Activitylog\Models\Activity;
use sanoha\Http\Controllers\Controller;
use sanoha\Http\Requests\RoleFormRequest;

class LogController extends Controller
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
    public function index()
    {   
        $latestActivities = Activity::with('user')->latest()->paginate(25);
        
        return view('logs.index', compact('latestActivities'));
    }
}
