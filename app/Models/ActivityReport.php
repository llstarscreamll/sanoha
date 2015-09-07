<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityReport extends Model
{
    use SoftDeletes;

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
     * Relación de Uno a Muchos.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function miningActivity()
    {
        return $this->belongsTo('sanoha\Models\MiningActivity');
    }
    
    /**
     * Relación de Uno a Muchos.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subCostCenter()
    {
        return $this->belongsTo('\sanoha\Models\SubCostCenter');
    }
    
    /**
     * Relación de Uno a Muchos.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('sanoha\Models\Employee');
    }
    
    /**
     * Relación de Uno a Muchos.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('sanoha\Models\User', 'reported_by');
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
     * @param array $parameters
     * 
     * @return object Collection
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
			->where('cost_centers.id', '=', $parameters['costCenter_id'])
			->whereNull('activity_reports.deleted_at');
		
		if(isset($parameters['employee']) && !empty($parameters['employee']))
		    $data = self::addSearchByEmployee($data, $parameters['employee']);
		
		if(isset($parameters['employee_id']) && !empty($parameters['employee_id']))
		    $data = self::addSearchByEmployeeId($data, $parameters['employee_id']);
		    
		$data = $data
		    ->whereBetween('activity_reports.reported_at', [$parameters['from'], $parameters['to']])
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
			->where('cost_centers.id', '=', $parameters['costCenter_id'])
			->whereNull('activity_reports.deleted_at');
		
		if(isset($parameters['employee']) && !empty($parameters['employee']))
		    $data = self::addSearchByEmployee($data, $parameters['employee']);
		
		if(isset($parameters['employee_id']) && !empty($parameters['employee_id']))
		    $data = self::addSearchByEmployeeId($data, $parameters['employee_id']);
		    
		$data = $data
		    ->whereBetween('activity_reports.reported_at', [$parameters['from'], $parameters['to']])
			->orderBy('employees.name')
			->get();

		return $data;
    }
    
    /**
     * Ordeno las activiaddes encontradas en un array para ser impreasas en la vista
     * 
     * @param array $activities
     * @return array
     */
    public static function sortActivities($activities)
    {
        // get sorted mining activities
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
                $ordered_activities[$value->employee_id]['employee_fullname'] = ucwords(strtolower($value->employee_name)) . ' ' . ucwords(strtolower($value->employee_lastname));
                $ordered_activities[$value->employee_id][$valueMiningActivity['short_name']]['quantity'] = 0;
                $ordered_activities[$value->employee_id][$valueMiningActivity['short_name']]['price'] = 0;

                $totals['totals']['totals'] = 'Total';
                $totals['totals'][$valueMiningActivity['short_name']]['quantity'] = 0;
                $totals['totals'][$valueMiningActivity['short_name']]['price'] = 0;
                
                //$totals['totals']['employees_totals']['price'] = 0;
               // $totals['totals']['employees_totals']['quantity'] = 0;
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
                    $ordered_activities[$value2->employee_id]['employee_total']['employee'] = ucwords(strtolower($value2->employee_name) . ' ' . strtolower($value2->employee_lastname));
                    
                    // calculing totals
                    $totals['totals'][$value['short_name']]['quantity'] += $value2->activity_quantity;
                    $totals['totals'][$value['short_name']]['price'] += ($value2->activity_quantity * $value2->activity_price);
                    
                    $employees_totals['employees_totals']['quantity'] += floatval($value2->activity_quantity);
                    $employees_totals['employees_totals']['price'] += floatval($value2->activity_quantity) * floatval($value2->activity_price);
                    
                    $totals['reported_by'][$value2->activity_reportedById] = $value2->activity_reportedByName . ' ' .$value2->activity_reportedByLastname;
                }
                
            }
            
        }
        
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
    public static function getHistoricalActivityPrice($mining_activity_id, $sub_cost_center_id)
    {
        $historical_activity = \sanoha\Models\ActivityReport::where('mining_activity_id', $mining_activity_id)
            ->where('sub_cost_center_id', $sub_cost_center_id)
            ->where('price', '!=', 0)
            ->orderBy('reported_at', 'desc')
            ->first();

        if($historical_activity)
            $price = $historical_activity->price;
        else {
            $price = 0;
        }
        
        return $price;
    }
}
