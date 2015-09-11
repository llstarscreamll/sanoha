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
     * Obtiene los empleados relacionados al subcentro de costo
     */
    public static function getRelatedEmployees($cost_center_id)
    {
        $subCostCenterEmployees = \sanoha\Models\SubCostCenter::where('cost_center_id', $cost_center_id)->with('employees')->get();
		
		$employees = [];
		
		foreach ($subCostCenterEmployees as $key => $subCostCenter) {

			$employees[$subCostCenter->name] = array();

			foreach ($subCostCenter->employees as $key_employee => $employee) {
				$employees[$subCostCenter->name][$employee->id] = $employee->fullname;
			}
			
		}
		
		return $employees;
    }
}
