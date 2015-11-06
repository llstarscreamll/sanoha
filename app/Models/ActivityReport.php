<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;


class ActivityReport extends Model implements LogsActivityInterface
{
    use SoftDeletes;
    use LogsActivity;

    /**
     * The timestamps.
     * 
     * @var array
     */ 
    protected $dates = ['reported_at', 'reported_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_id', 'sub_cost_center_id', 'mining_activity_id', 'quantity', 'price', 'comment', 'reported_by', 'reported_at'];
    
    /**
     * Get the message that needs to be logged for the given event name.
     *
     * @param string $eventName
     * @return string
     */
    public function getActivityDescriptionForEvent($eventName)
    {
        $data = [
            'nombre_centro_costo'   =>  $this->subCostCenter->costCenter->name,
            'nombre_sub_centro'     =>  $this->subCostCenter->name,
            'nombres_empleado'      =>  $this->employee->fullname,
            'nombre_actividad'      =>  $this->miningActivity->name,
            
            'cantidad'              =>  $this->quantity,
            'precio'                =>  $this->price,
            'comentario'            =>  $this->comment,
            'nombre_quien_reporta'  =>  $this->user->fullname,
            
            'id_reporte'            =>  $this->id,
            'id_centro_costo'       =>  $this->subCostCenter->costCenter->id,
            'id_sub_centro_costo'   =>  $this->sub_cost_center_id,
            'id_empleado'           =>  $this->employee_id,
            'id_actividad'          =>  $this->mining_activity_id,
            'id_quien_reporta'      =>  $this->reported_by,
        ];
        
        if ($eventName == 'created')
        {
            return '<strong>@user</strong> reportó una actividad minera, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|ActivityReport' // de qué modulo
                    .'|create' // la acción
                    .'|activityReport.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|success' // la clase css que tendrá este registro
                    ;
        }
    
        if ($eventName == 'updated')
        {
            return '<strong>@user</strong> actualizó el reporte una actividad minera, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|ActivityReport' // de qué modulo
                    .'|update' // la acción
                    .'|activityReport.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|warning' // la clase css que tendrá este registro
                    ;
        }
    
        if ($eventName == 'deleted')
        {
            return '<strong>@user</strong> eliminó el reporte una actividad minera, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|ActivityReport' // de qué modulo
                    .'|delete' // la acción
                    .'|activityReport.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|danger' // la clase css que tendrá este registro
                    ;
        }
    
        return '';
    }
    
    /**
     * Relación de Uno a Muchos.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function miningActivity()
    {
        return $this->belongsTo('sanoha\Models\MiningActivity')->withTrashed();
    }
    
    /**
     * Relación de Uno a Muchos.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subCostCenter()
    {
        return $this->belongsTo('\sanoha\Models\SubCostCenter')->withTrashed();
    }
    
    /**
     * Relación de Uno a Muchos.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('sanoha\Models\Employee')->withTrashed();
    }
    
    /**
     * Relación de Uno a Muchos.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('sanoha\Models\User', 'reported_by')->withTrashed();
    }
    
    /**
     * @param string $employee Los nombres, apellidos o cédula del empleado.
     * 
     * @return  {object}
     */
    public static function addSearchByEmployee($query, $employee)
    {
        if(!empty( trim($employee) ))
           return $query->where(function($query) use ($employee){
                $query->orWhere('employees.identification_number', 'LIKE', '%'.$employee.'%') // puede ser por número de identificación
                    ->orWhere('employees.name', 'LIKE', '%'.$employee.'%') // puede ser por nombre
                    ->orWhere('employees.lastname', 'LIKE', '%'.$employee.'%'); // puede ser por apellido
            });
    }
    
    /**
     * @param int $employee_id El id del empleado
     * 
     * @return {object}
     */
    public static function addSearchByEmployeeId($query, $employee_id)
    {
        if(!empty( trim($employee_id) )){
            return $query->where('employees.id', '=', $employee_id);
        }
    }
    
    /**
     * Obtengo las actividades mineras según los parámetros de búsqueda, que puedría
     * ser por un rango de fechas, por nombres, apellidos o cédula del empleado.
     * 
     * @param   sanoha\Http\RequestsActivityReportFormRequest	$requests
     * @return  object Collection
     */
    public static function getActivities($parameters)
    {
        $data = \DB::table('activity_reports')
            // datos de las labores mineras
            ->join('mining_activities', 'activity_reports.mining_activity_id', '=', 'mining_activities.id')
            // datos del empleado
            ->join('employees', 'activity_reports.employee_id', '=', 'employees.id')
            // info de subcentro de costo
            ->join('sub_cost_centers', 'activity_reports.sub_cost_center_id', '=', 'sub_cost_centers.id')
            // info de centro de costo
            ->join('cost_centers', 'sub_cost_centers.cost_center_id', '=', 'cost_centers.id')
            // info de usuarios
            ->join('users', 'activity_reports.reported_by', '=', 'users.id' )
            
            ->select(
                'employees.id as employee_id',
                'employees.name as employee_name',
                'employees.lastname as employee_lastname',
                'activity_reports.mining_activity_id as activity_id',
                'activity_reports.reported_at as activity_date',
                'activity_reports.quantity as activity_quantity',
                'activity_reports.price as activity_price',
                'users.id as activity_reportedById',
                'users.name as activity_reportedByName',
                'users.lastname as activity_reportedByLastname',
                'mining_activities.name as activity_name',
                'mining_activities.short_name as activity_shortname',
                'cost_centers.name as costCenter_name')
                
            // solo los empleados de cierto centro de costo deben aparecer
            ->where('cost_centers.id', '=', $parameters['cost_center_id']);
        
        if(isset($parameters['employee']) && !empty($parameters['employee']))
            $data = self::addSearchByEmployee($data, $parameters['employee']);
        
        if(isset($parameters['employee_id']) && !empty($parameters['employee_id']))
            $data = self::addSearchByEmployeeId($data, $parameters['employee_id']);
            
        $data = $data
            ->whereBetween('activity_reports.reported_at', [$parameters['from'], $parameters['to']])
            ->whereNull('activity_reports.deleted_at')
            ->orderBy('employees.name')
            ->get();

        return $data;
    }
    
    /**
     * Obtiene datos para la vista calendario
     */ 
    public static function getCalendarActivities($parameters)
    {
        $data = \DB::table('activity_reports')
            // datos de las labores mineras
            ->join('mining_activities', 'activity_reports.mining_activity_id', '=', 'mining_activities.id')
            // info de usuarios
            // datos del empleado
            ->join('employees', 'activity_reports.employee_id', '=', 'employees.id')
            // info de subcentro de costo
            ->join('sub_cost_centers', 'activity_reports.sub_cost_center_id', '=', 'sub_cost_centers.id')
            // info de centro de costo
            ->join('cost_centers', 'sub_cost_centers.cost_center_id', '=', 'cost_centers.id')
            // info de usuarios
            ->join('users', 'activity_reports.reported_by', '=', 'users.id' )
            
            ->select(
                array(
                    'activity_reports.id as id',
                    'employees.id as employee_id',
                    \DB::raw('concat(sws_activity_reports.quantity, " ", sws_mining_activities.name, " a $", sws_activity_reports.price, " (c/u) por ", sws_employees.name, " ", sws_employees.lastname) as title'),
                    \DB::raw('concat("'. url('activityReport') .'/", sws_activity_reports.id) as url'),
                    \DB::raw('concat("event-success") as class'),
                    \DB::raw('concat(UNIX_TIMESTAMP( concat(STR_TO_DATE(sws_activity_reports.reported_at, "%Y-%m-%d"), " 06:00:00") ), "000") as start'), // start
                    \DB::raw('concat(UNIX_TIMESTAMP( concat(STR_TO_DATE(sws_activity_reports.reported_at, "%Y-%m-%d"), " 18:00:00") ), "000") as end'), // start
                    )
                )
            // solo los empleados de cierto centro de costo deben aparecer
            ->where('cost_centers.id', '=', $parameters['cost_center_id']);
        
        if(isset($parameters['employee']) && !empty($parameters['employee']))
            $data = self::addSearchByEmployee($data, $parameters['employee']);
        
        if(isset($parameters['employee_id']) && !empty($parameters['employee_id']))
            $data = self::addSearchByEmployeeId($data, $parameters['employee_id']);
            
        $data = $data
            ->whereBetween('activity_reports.reported_at', [$parameters['from'], $parameters['to']])
            ->whereNull('activity_reports.deleted_at')
            ->orderBy('employees.name')
            ->get();
        dd($data);
        return json_encode($data);
    }
    
    /**
     * Ordeno las activiaddes encontradas en un array para ser impreasas en la vista
     * 
     * @param array $activities
     * @return array
     */
    public static function sortedActivities($parameters)
    {
        $activities = self::getActivities($parameters);
        
        // get mining activities from DB
        $miningActivities = \sanoha\Models\MiningActivity::customOrder();
        $ordered_activities = [];
        $totals = [];
        $employees_totals['employees_totals']['quantity'] = 0;
        $employees_totals['employees_totals']['price'] = 0;
        $employees_totals['employees_totals']['employee'] = '';

        // create array indexes (columns) with miningActivities table values
        foreach($activities as $key => $value){
            
            // creating indexes (columns)
            foreach ($miningActivities as $keyMiningActivity => $valueMiningActivity) {
                $ordered_activities[$value->employee_id]['employee_fullname'] = ucwords(strtolower($value->employee_lastname . ' ' . $value->employee_name));
                $ordered_activities[$value->employee_id][$valueMiningActivity['short_name']]['quantity'] = 0;
                $ordered_activities[$value->employee_id][$valueMiningActivity['short_name']]['price'] = 0;

                $totals['totals']['totals'] = 'Total';
                $totals['totals'][$valueMiningActivity['short_name']]['quantity'] = 0;
                $totals['totals'][$valueMiningActivity['short_name']]['price'] = 0;
                
            }
            
            $ordered_activities[$value->employee_id]['employee_total']['quantity'] = 0;
            $ordered_activities[$value->employee_id]['employee_total']['price'] = 0;
        }
        
        // asign values to above columns
        foreach ($miningActivities as $key => $value) {
            
            foreach ($activities as $key2 => $value2) {
                
                if($value['short_name'] === $value2->activity_shortname){
                    $ordered_activities[$value2->employee_id][$value2->activity_shortname]['quantity'] += floatval($value2->activity_quantity);
                    $ordered_activities[$value2->employee_id][$value2->activity_shortname]['price'] += floatval($value2->activity_quantity) * floatval($value2->activity_price);
                    
                    $ordered_activities[$value2->employee_id]['employee_total']['quantity'] += floatval($value2->activity_quantity);
                    $ordered_activities[$value2->employee_id]['employee_total']['price'] += floatval($value2->activity_quantity) * floatval($value2->activity_price);
                    $ordered_activities[$value2->employee_id]['employee_total']['employee'] = ucwords(strtolower($value2->employee_lastname . ' ' . $value2->employee_name));
                    
                    // calculing totals
                    $totals['totals'][$value['short_name']]['quantity'] += $value2->activity_quantity;
                    $totals['totals'][$value['short_name']]['price'] += ($value2->activity_quantity * $value2->activity_price);
                    
                    $employees_totals['employees_totals']['quantity'] += floatval($value2->activity_quantity);
                    $employees_totals['employees_totals']['price'] += floatval($value2->activity_quantity) * floatval($value2->activity_price);
                    
                    $totals['reported_by'][$value2->activity_reportedById] = $value2->activity_reportedByName . ' ' .$value2->activity_reportedByLastname;
                }
                
            }
            
        }
        
        if(isset($totals['totals']))
            array_push($totals['totals'], $employees_totals['employees_totals']);

        return array_merge($ordered_activities, $totals);
    }
    
    /**
     * 
     */ 
    public function getReportersFullname()
    {
        $reporters = [];
        
        foreach ($this->user() as $user) {
            $reporters[] .= $user->name . ' ' . $user->lastname;
        }
        
        return $reporters;
    }
    
    /**
     * Obtiene el precio histórico de una actividad en un subcentro de costo específico
     * 
     * @param   int     $mining_activity_id
     * @param   int     $sub_cost_center_id
     * 
     * @return  int
     */
    public static function getHistoricalActivityPrice($mining_activity_id, $sub_cost_center_id, $employee_id)
    {
        $historical_activity = \sanoha\Models\ActivityReport::where('mining_activity_id', $mining_activity_id)
            ->where('sub_cost_center_id', $sub_cost_center_id)
            ->where('price', '!=', 0)
            ->where('employee_id', $employee_id)
            ->orderBy('reported_at', 'desc')
            ->first();

        if($historical_activity)
            $price = $historical_activity->price;
        else {
            $price = 0;
        }
        
        return $price;
    }
    
    /**
     * La consulta de donde se obtienen los datos a mostrar en la vista "Reporte
     * individual de actividades mineras reportadas"....
     * 
     * @param   array   $parameters
     * @return  Collection
     */ 
    public static function individualSearch($parameters)
    {
        return \sanoha\Models\ActivityReport::with('employee', 'miningActivity')
            ->where('reported_at', '>=', $parameters['from'])
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
     * Consulta si se ha reportado una actividad minera en determinada fecha, para
     * evitar qu se reporten dos actividades mineras el mismo día.
     * 
     * @param   array   $data
     * @return  mixed
     */
    public static function alreadyMiningActivityReported($data)
    {
        return \sanoha\Models\ActivityReport::where(function($q) use ($data){
            $q->where('employee_id', $data['employee_id'])
                ->where('mining_activity_id', $data['mining_activity_id'])
                ->whereBetween(
                    'reported_at',
                    [
                        $data['reported_at']->copy()->startOfDay()->toDateTimeString(),
                        $data['reported_at']->copy()->endOfDay()->toDateTimeString()
                    ]
                );
        })->first();
    }
    
    /**
     * Configura los parámetros para los filtros o búsquedas en los reportes, por
     * defecto se hacen búsquedas de sólo el mes en curso, en el tercer parámetro
     * se puede definir fechas opcionales a las de
     * 
     * @param   sanoha\Http\RequestsActivityReportFormRequest	$requests
     * @param   string  $cost_center_id
     * @para    array   $options
     * @return  array
     */
    public static function configureParameters($request, $cost_center_id, $options = array())
    {
        $parameters = [];

        $start = $request->has('from')
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('from'))->startOfDay()
            : \Carbon\Carbon::now()->startOfMonth()->startOfDay();

        $end = $request->has('to')
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('to'))->endOfDay()
            : \Carbon\Carbon::now()->endOfMonth()->endOfDay();
        
        $parameters['employee'] 		= !empty($request->get('find')) ? $request->get('find') : null;
        $parameters['employee_id'] 		= $request->get('employee_id', null);
        // en caso de que se quiera fechas diferentes a la predeterminadas
        $parameters['from'] 			= isset($options['start']) && empty($request->get('from')) ? $options['start'] : $start;
        $parameters['to'] 				= isset($options['end']) && empty($request->get('to')) ? $options['end'] : $end;
        $parameters['cost_center_id'] 	= $cost_center_id;
        $parameters['cost_center_name'] = \Session::get('current_cost_center_name');
        
        return $parameters;
    }
}
