<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityReport extends Model
{
    use SoftDeletes;

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
     * 
     */
    public function miningActivity()
    {
        return $this->belongsTo('sanoha\Models\MiningActivity');
    }
    
    /**
     * 
     */
    public function employee()
    {
        return $this->belongsTo('sanoha\Models\Employee');
    }
    
    /**
     * 
     */
    public function user()
    {
        return $this->belongsTo('sanoha\Models\User', 'reported_by');
    }
    
    /**
     * 
     * 
     * @return {object}
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
     * 
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
     * 
     */
    public static function getActivities($parameters)
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
			->where('cost_centers.id', '=', $parameters['costCenter_id']);
		
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
                    \DB::raw('concat(UNIX_TIMESTAMP(STR_TO_DATE(sws_activity_reports.reported_at, "%Y-%m-%d %H:%i:%s")), "000") as start'), // start
                    \DB::raw('concat(UNIX_TIMESTAMP( concat(STR_TO_DATE(sws_activity_reports.reported_at, "%Y-%m-%d"), " 18:00:00") ), "000") as end'), // start
                    //'' // end
                    )
				)
            // solo los empleados de cierto centro de costo deben aparecer
			->where('cost_centers.id', '=', $parameters['costCenter_id']);
		
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
     * 
     */
    public static function sortActivities($activities)
    {
        // get sorted mining activities
        $miningActivities = \sanoha\Models\MiningActivity::orderBy('short_name')->get();
        
        $ordered_activities = [];
        $totals = [];

        // create array indexes (columns) with miningActivities table values
        foreach($activities as $key => $value){
            
            // creating indexes (columns)
            foreach ($miningActivities as $keyMiningActivity => $valueMiningActivity) {
                
                $ordered_activities[$value->employee_id]['employee_fullname'] = ucwords(strtolower($value->employee_name)) . ' ' . ucwords(strtolower($value->employee_lastname));
                $ordered_activities[$value->employee_id][$valueMiningActivity->short_name]['quantity'] = 0;
                $ordered_activities[$value->employee_id][$valueMiningActivity->short_name]['price'] = 0;
                $ordered_activities[$value->employee_id][$valueMiningActivity->short_name]['unit_price'] = 0;
                
                $totals['totals']['totals'] = 'Total';
                $totals['totals'][$valueMiningActivity->short_name]['quantity'] = 0;
                $totals['totals'][$valueMiningActivity->short_name]['price'] = 0;

            }
        }
        
        // asign values to above columns
        foreach ($miningActivities as $key => $value) {
            
            foreach ($activities as $key2 => $value2) {
                
                if($value->short_name === $value2->activity_shortname){
                    $ordered_activities[$value2->employee_id][$value2->activity_shortname]['quantity'] += floatval($value2->activity_quantity);
                    $ordered_activities[$value2->employee_id][$value2->activity_shortname]['price'] += (floatval($value2->activity_quantity) * floatval($value2->activity_price));

                    // calculing totals
                    $totals['totals'][$value->short_name]['quantity'] += $value2->activity_quantity;
                    $totals['totals'][$value->short_name]['price'] += ($value2->activity_quantity * $value2->activity_price);
                    
                    //$totals['reported_by'][$value2->activity_reportedById] = $value2->activity_reportedById;
                    $totals['reported_by'][$value2->activity_reportedById] = $value2->activity_reportedByName . ' ' .$value2->activity_reportedByLastname;
                }
                
            }
            
        }
        
        return array_merge($ordered_activities, $totals);
                
    }
    
    public function getReportersFullname()
    {
        $reporters = [];
        
        foreach ($this->user() as $user) {
            $reporters[] .= $user->name . ' ' . $user->lastname;
        }
        
        return $reporters;
    }
    
}
