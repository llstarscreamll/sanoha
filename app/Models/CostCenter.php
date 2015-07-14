<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostCenter extends Model
{
	use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
	
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cost_centers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    
    /**
     * 
     */ 
    public function employee()
    {
        return $this->hasMany('sanoha\Models\Employee');
    }
    
    /**
     * 
     */
    public function user()
    {
        return $this->belongsToMany('sanoha\Models\User', 'cost_center_owner');
    }

}
