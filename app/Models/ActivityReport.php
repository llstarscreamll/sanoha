<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityReport extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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
    protected $fillable = ['employee_id', 'mining_activity_id', 'quantity', 'price', 'comment', 'reported_by'];
    
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
			->join('employees', 'activity_reports.employee_id', '=', 'employees.id')
			->join('cost_centers', 'employees.cost_center_id', '=', 'cost_centers.id')
			->join('mining_activities', 'activity_reports.mining_activity_id', '=', 'mining_activities.id')
			->join('users', 'activity_reports.reported_by', '=', 'users.id' )
			->select(
				'employees.id as employee_id',
				'employees.name as employee_name',
				'employees.lastname as employee_lastname',
				'activity_reports.mining_activity_id as activity_id',
				'activity_reports.created_at as activity_date',
				'activity_reports.quantity as activity_quantity',
				'users.id as activity_reportedById',
				'users.name as activity_reportedByName',
				'users.lastname as activity_reportedByLastname',
				'mining_activities.name as activity_name',
				'mining_activities.short_name as activity_shortname',
				'cost_centers.name as costCenter_name')
			->where('employees.cost_center_id', '=', $parameters['costCenter_id']);
		
		if(isset($parameters['employee']) && !empty($parameters['employee']))
		    $data = self::addSearchByEmployee($data, $parameters['employee']);
		
		if(isset($parameters['employee_id']) && !empty($parameters['employee_id']))
		    $data = self::addSearchByEmployeeId($data, $parameters['employee_id']);
		    
		$data = $data
		    ->whereBetween('activity_reports.created_at', [$parameters['from'], $parameters['to']])
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
                
                $ordered_activities[$value->employee_id]['employee_fullname'] = $value->employee_name . ' ' . $value->employee_lastname;
                $ordered_activities[$value->employee_id][$valueMiningActivity->short_name] = 0;
                
                $totals['totals']['totals'] = 'Total';
                $totals['totals'][$valueMiningActivity->short_name] = 0;
                
            }
        }
        
        // asign values to above columns
        foreach ($miningActivities as $key => $value) {
            
            foreach ($activities as $key2 => $value2) {
                
                if($value->short_name === $value2->activity_shortname){
                    $ordered_activities[$value2->employee_id][$value2->activity_shortname] += $value2->activity_quantity;
                    
                    // calculing totals
                    $totals['totals'][$value->short_name] += $value2->activity_quantity;
                    
                    //$totals['reported_by'][$value2->activity_reportedById] = $value2->activity_reportedById;
                    $totals['reported_by'][$value2->activity_reportedById] = $value2->activity_reportedByName . ' ' .$value2->activity_reportedByLastname;
                }
                
            }
            
        }
        
        //dd(array_merge($ordered_activities, $totals), $activities);
        
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
