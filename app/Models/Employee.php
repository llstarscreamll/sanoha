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
        return $this->attributes['name'] .' '. $this->attributes['lastname'];
    }
}
