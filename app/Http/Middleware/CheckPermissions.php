<?php namespace sanoha\Http\Middleware;

use Closure;
//use \Illuminate\Routing\Route;

class CheckPermissions {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		
		$action = $request->route()->getName();
		
		if(! \Auth::getUser()->can($action)){
			
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect()->to('/home')->with('warning', 'No tienes los permisos necesarios para realizar esta acción.');
			}
			
		}
		
		return $next($request);
	}

}
