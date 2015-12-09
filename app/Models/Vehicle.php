<?php
namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['plate', 'passengers', 'description'];

    /**
     * La relación entre evhículos y ordenes de trabajo, uno a muchos
     */
    public function workOrders()
    {
        return $this->hasMany('sanoha\Models\WorkOrder');
    }
}
