<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
	use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
	
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sub_cost_center_id', 'position_id', 'name', 'lastname', 'identification_number', 'email'];
    
    /**
     * La relación entre un empledo y las ordenes de trabajo, muchos a muchos
     */
    public function internalAccompanists()
    {
        return $this->belongsToMany('sanoha\Models\WorkOrder', 'internal_accompanists')
            ->withPivot('work_report', 'reported_by', 'reported_at');
    }
    
    /**
     * 
     */
    public function subCostCenter()
    {
        return $this->belongsTo('sanoha\Models\SubCostCenter');
    }
    
    /**
     * 
     */ 
    public function position()
    {
        return $this->belongsTo('sanoha\Models\Position');
    }
    
    /**
     * 
     */
    public function noveltyReport()
    {
        return $this->hasMany('sanoha\Models\NoveltyReport');
    }
    
    /**
     * 
     */
    public function activityReport()
    {
        return $this->hasMany('sanoha\Models\ActivityReport');
    }
    
    /**
     * 
     */
    public function getFullnameAttribute()
    {
        return ucwords(strtolower($this->attributes['lastname'] .' '. $this->attributes['name']));
    }
}
