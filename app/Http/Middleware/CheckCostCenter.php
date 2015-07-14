<?php namespace sanoha\Http\Middleware;

use Closure;
//use \Illuminate\Routing\Route;

class CheckCostCenter {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$cost_center = \Session::get('currentCostCenterActivities');
		
		// si no se ha seteado el centro de costo, es decir, si no se ha seleecionado
		if(is_null($cost_center)){

			if ($request->ajax())
			{
				return response('Debes seleccionar un centro de costos.', 403);
			}
			else
			{
				return redirect()->route('activityReport.selectCostCenter');
			}
			
		}
		
		$cost_center = \sanoha\Models\CostCenter::find($cost_center);
		
		// si el centro de costo no existe
		if( is_null($cost_center) ){
		    
		    if ($request->ajax())
			{
				return response('No se ha encontrado el recurso que buscas.', 404);
			}
			else
			{
				return abort(404, 'Recurso no encontrado.');;
			}
			
		}
		
		// si el usuario intenta acceder a un centro de costo que no tiene asignado
		if(! \Auth::getUser()->hasCostCenter($cost_center->id)){

		    if ($request->ajax())
			{
				return response('No tienes acceso a estos datos.', 403);
			}
			else
			{
				return redirect()->to('/home')->with('warning', 'No tienes los permisos necesarios para acceder a estos datos.');
			}
			
		}
		
		return $next($request);
	}

}
