<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCostCenter extends Model {

	use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
	
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sub_cost_centers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sub_cost_center_id', 'name', 'short_name'];
    
    /**
     * La relación con el modelo de centros de costo
     */
    public function costCenter()
    {
        return $this->belongsTo('sanoha\Models\CostCenter');
    }
    
    /**
    * La relación con el modelo de usuarios
    */
    public function user()
    {
        return $this->belongsToMany('sanoha\Models\User', 'sub_cost_center_owner');
    }

    /**
     * 
     */ 
    public function employees()
    {
        return $this->hasMany('sanoha\Models\Employee');
    }
    
    /**
     * Obtiene los empleados relacionados al centro de costo e incluye ($include)
     * o excluye ($exclude) a un empleado si es que no se encuentra relacionado
     * al centro de costo mediante el id según se expecifique en los parámetros...
     * 
     * @param   string  $cost_center_id
     * @param   string  $include
     * @param   string  $exclude
     * @return  array
     */
    public static function getRelatedEmployees($cost_center_id = null, $include = null, $exclude = null)
    {
        $centerEmployees = is_null($cost_center_id)
            ? \sanoha\Models\CostCenter::with('employees')->get()
            : \sanoha\Models\SubCostCenter::where('cost_center_id', $cost_center_id)->with('employees')->get();
		$found_include = false;
		$employees = [];
		
		foreach ($centerEmployees as $key => $center) {

			$employees[$center->name] = array();

			foreach ($center->employees as $key_employee => $employee) {
			    
			    // excluyo a un empleado si es especificado
			    if($employee->id !== $exclude)
				    $employees[$center->name][$employee->id] = $employee->fullname;
				
				if($employee->id === $include)
				    $found_include = true;
			}
			
		}
		
		if(!$found_include && !is_null($include)){
		    $employee_to_include = \sanoha\Models\Employee::withTrashed()->where('id', $include)->first();
		    $employees = [$employee_to_include->id => $employee_to_include->fullname ] + $employees;
		}
		
		return $employees;
    }
    
    /**
     * Devuelve un nombre mas completo del subcentro de costo al unirlo con el
     * nombre del Centro de Costo al que pertenece
     */
    public function getNameWithCostCenterNameAttribute()
    {
        return $this->costCenter->name.' '.$this->name;
    }
}
