<?php namespace sanoha\Http\Controllers;

use sanoha\Http\Controllers\Controller;

use Illuminate\Http\Request;

use sanoha\Http\Requests\RoleFormRequest;
use \sanoha\Models\Role;
use \sanoha\Models\Permission;

use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{

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
