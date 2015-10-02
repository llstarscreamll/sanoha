<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoveltyReport extends Model
{
	use SoftDeletes;

    protected $dates = ['reported_at', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'novelty_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sub_cost_center_id', 'employee_id', 'novelty_id', 'comment', 'reported_at'];
    
    /**
     * 
     */ 
    public function subCostCenter()
    {
        return $this->belongsTo('\sanoha\Models\SubCostCenter')->withTrashed();
    }
    
    /**
     * 
     */ 
    public function novelty()
    {
        return $this->belongsTo('\sanoha\Models\Novelty')->withTrashed();
    }
    
    /**
     * 
     */
    public function employee()
    {
        return $this->belongsTo('\sanoha\Models\Employee')->withTrashed();
    }
    
    /**
     * Obtengo las novedades reportadas en cierto periodo de tiempo con formato json
     */
    public static function getCalendarNovelties($parameters)
    {
        $cost_center_id = \Session::get('current_cost_center_id');
        $data = [];
        $i = 0;
        
        $novelties = \sanoha\Models\NoveltyReport::where(function ($q) use ($parameters){
                $q  ->where('reported_at', '>', $parameters['from'])
                    ->where('reported_at', '<', $parameters['to']);
            })
            ->whereHas('employee', function($q) use ($parameters)
				{
				    $q->where(function($q) use ($parameters){
				    	$q->where('name', 'like', '%'.$parameters["employee"].'%')
				    		->orWhere('lastname', 'like', '%'.$parameters["employee"].'%')
				    		->orWhere('identification_number', 'like', '%'.$parameters["employee"].'%');
				    });
				
				})
            ->whereHas('subCostCenter', function($q) use ($parameters){
                $q  ->where('cost_center_id', $parameters['cost_center_id']);
            })
            ->orderBy('reported_at', 'asc')
            ->get();
            
        foreach ($novelties as $key => $novelty) {
            
            $data[$i] = [
                'id'            =>  $novelty->id,
                'employee_id'   =>  $novelty->employee_id,
                'title'         =>  ucwords(strtolower($novelty->employee->fullname)).' reportó '.
                                    $novelty->novelty->name,
                'url'           =>  route('noveltyReport.show', $novelty->id),
                'class'         =>  'event-important',
                'start'         =>  $novelty->reported_at->startOfDay()->timestamp.'000',
                'end'           =>  $novelty->reported_at->endOfDay()->timestamp.'000'
            ];
            
            $i++;
        }
        
        return json_encode($data);
    }
    
    /**
     * Obtengo los registros individuales de las novedades reportadas
     * 
     * @param   array   $parameters
     * @return  Collection
     */
    public static function individualNovelties($parameters)
    {
        return \sanoha\Models\NoveltyReport::where('reported_at', '>=', $parameters['from'])
			->where('reported_at', '<=', $parameters['to'])
			->orderBy('updated_at', 'desc')
			->whereHas('employee', function($q) use ($parameters)
				{
				    $q->where(function($q) use ($parameters){
				    	$q->where('name', 'like', '%'.$parameters["employee"].'%')
				    		->orWhere('lastname', 'like', '%'.$parameters["employee"].'%')
				    		->orWhere('identification_number', 'like', '%'.$parameters["employee"].'%');
				    });
				
				})
			->whereHas('subCostCenter', function($q) use ($parameters){
				$q->where('cost_center_id', $parameters['cost_center_id']);
			})
			->paginate(15);
    }
    
    /**
     * Configuro los parámetros de búsqueda de actividades
     * 
     * @param   sanoha\Http\RequestsActivityReportFormRequest	$requests
     * @param   string  $cost_center_id
     * @para    array   $options
     * @return  array
     */
    public static function configureParameters($request, $cost_center_id, $options = array())
    {
        $parameters = array();
        
        $start = $request->has('from')
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('from'))->startOfDay()
            : \Carbon\Carbon::now()->startOfYear()->startOfDay();
            
        $end = $request->has('to')
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('to'))->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();
        
        $parameters['employee'] 		= !empty($request->get('find')) ? $request->get('find') : null;
        // en caso de que se quiera fechas diferentes a las predeterminadas
		$parameters['from'] 			= isset($options['start']) && empty($request->get('to')) ? $options['start'] : $start;
		$parameters['to'] 				= isset($options['end']) && empty($request->get('to')) ? $options['end'] : $end;
		$parameters['cost_center_id'] 	= $cost_center_id;
		$parameters['cost_center_name'] = \Session::get('current_cost_center_name');
		
		return $parameters;
    }
}
