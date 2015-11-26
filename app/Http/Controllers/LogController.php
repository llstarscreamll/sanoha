<?php
namespace sanoha\Http\Controllers;

use \sanoha\Models\Role;
use Illuminate\Http\Request;
use \sanoha\Models\Permission;
use sanoha\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use sanoha\Http\Requests\RoleFormRequest;

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
