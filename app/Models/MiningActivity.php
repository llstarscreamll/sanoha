<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MiningActivity extends Model
{
	use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mining_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'short_name'];
    
    /**
     * 
     */ 
    public function activityReport()
    {
        return $this->hasMany('\sanoha\Models\ActivityReport');
    }
    
    /**
     * 
     */
    public function getNameAndAbbreviationAttribute()
    {
        return $this->attributes['name'] . ' | ' . $this->attributes['short_name'];
    }

}
