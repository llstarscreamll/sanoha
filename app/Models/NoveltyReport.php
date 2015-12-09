<?php
namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;

class NoveltyReport extends Model implements LogsActivityInterface
{
    use SoftDeletes;
    use LogsActivity;

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
     * Get the message that needs to be logged for the given event name.
     *
     * @param string $eventName
     * @return string
     */
    public function getActivityDescriptionForEvent($eventName)
    {
        $data = [
            'nombre_centro_costo'       =>  $this->subCostCenter->costCenter->name,
            'nombre_sub_centro_costo'   =>  $this->subCostCenter->name,
            'nombres_empleado'          =>  $this->employee->fullname,
            'nombre_novedad'            =>  $this->novelty->name,
            
            'comentario'                =>  $this->comment,

            'id_reporte'                =>  $this->id,
            'id_centro_costo'           =>  $this->subCostCenter->costCenter->id,
            'id_sub_centro_costo'       =>  $this->sub_cost_center_id,
            'id_empleado'               =>  $this->employee_id,
            'id_novedad'                =>  $this->novelty_id,
        ];
        
        if ($eventName == 'created') {
            return '<strong>@user</strong> reportó una actividad minera, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|NoveltyReport' // de qué modulo
                    .'|create' // la acción
                    .'|noveltyReport.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|success' // la clase css que tendrá este registro
                    ;
        }
    
        if ($eventName == 'updated') {
            return '<strong>@user</strong> actualizó una novedad, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|NoveltyReport' // de qué modulo
                    .'|update' // la acción
                    .'|noveltyReport.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|warning' // la clase css que tendrá este registro
                    ;
        }
    
        if ($eventName == 'deleted') {
            return '<strong>@user</strong> eliminó una novedad, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|NoveltyReport' // de qué modulo
                    .'|delete' // la acción
                    .'|noveltyReport.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|danger' // la clase css que tendrá este registro
                    ;
        }
    
        return '';
    }
    
    /**
     * La relación entre reporte de novedades y subcentros de costo, uno a muchos
     */
    public function subCostCenter()
    {
        return $this->belongsTo('\sanoha\Models\SubCostCenter')->withTrashed();
    }
    
    /**
     * La relación entre reporte de novedades y tipos de novedades, uno a muchos
     */
    public function novelty()
    {
        return $this->belongsTo('\sanoha\Models\Novelty')->withTrashed();
    }
    
    /**
     * La relación entre reporte de novedades y empleados, uno a muchos
     */
    public function employee()
    {
        return $this->belongsTo('\sanoha\Models\Employee')->withTrashed();
    }
    
    /**
     * Obtiene las novedades reportadas en cierto periodo de tiempo en formato json
     * 
     * @param   array   $parameters
     * @return  json
     */
    public static function getCalendarNovelties($parameters)
    {
        $cost_center_id = \Session::get('current_cost_center_id');
        $data = [];
        $i = 0;
        
        $novelties = \sanoha\Models\NoveltyReport::where(function ($q) use ($parameters) {
                $q  ->where('reported_at', '>', $parameters['from'])
                    ->where('reported_at', '<', $parameters['to']);
            })
            ->whereHas('employee', function ($q) use ($parameters) {
                    $q->where(function ($q) use ($parameters) {
                        $q->where('name', 'like', '%'.$parameters["employee"].'%')
                            ->orWhere('lastname', 'like', '%'.$parameters["employee"].'%')
                            ->orWhere('identification_number', 'like', '%'.$parameters["employee"].'%');
                    });
                
                })
            ->whereHas('subCostCenter', function ($q) use ($parameters) {
                $q  ->where('cost_center_id', $parameters['cost_center_id']);
            })
            ->with('employee', 'novelty')
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
        return \sanoha\Models\NoveltyReport::with('employee', 'novelty')
            ->where('reported_at', '>=', $parameters['from'])
            ->where('reported_at', '<=', $parameters['to'])
            ->orderBy('updated_at', 'desc')
            ->whereHas('employee', function ($q) use ($parameters) {
                    $q->where(function ($q) use ($parameters) {
                        $q->where('name', 'like', '%'.$parameters["employee"].'%')
                            ->orWhere('lastname', 'like', '%'.$parameters["employee"].'%')
                            ->orWhere('identification_number', 'like', '%'.$parameters["employee"].'%');
                    });
                
                })
            ->whereHas('subCostCenter', function ($q) use ($parameters) {
                $q->where('cost_center_id', $parameters['cost_center_id']);
            })
            ->paginate(15);
    }
    
    /**
     * Configuro los parámetros de búsqueda de actividades
     * 
     * @param   sanoha\Http\RequestsActivityReportFormRequest	$requests
     * @param   string  $cost_center_id
     * @param   array   $options
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
        
        $parameters['employee']        = !empty($request->get('find')) ? $request->get('find') : null;
        // en caso de que se quiera fechas diferentes a las predeterminadas
        $parameters['from']            = isset($options['start']) && empty($request->get('to')) ? $options['start'] : $start;
        $parameters['to']                = isset($options['end']) && empty($request->get('to')) ? $options['end'] : $end;
        $parameters['cost_center_id']    = $cost_center_id;
        $parameters['cost_center_name'] = \Session::get('current_cost_center_name');
        
        return $parameters;
    }

    /**
     * Obtiene los cargos de los empleados que serán cargados en el módulo en operaciones 
     * como el reporte de novedades del personal minero, asì se puede definir cuales trabajadores deben
     * aparecer, como sòlo mineros y supervisores de proyectos mineros.
     * 
     * @return array
     */
    public static function getPositionsToInclude()
    {
        // de momento se deja ésta configuración estática, a futuro tiene
        // que ser dinámica
        return [1, 2]; // Minero y Supervisor
    }
}
