<?php
namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMovement extends Model
{

    protected $dates = ['created_at', 'updated_at'];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vehicle_movements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'work_order_id',
        'registered_by',
        'mileage',
        'fuel_level',
        'internal_cleanliness',
        'external_cleanliness',
        'paint_condition',
        'bodywork_condition',
        'right_front_wheel_condition',
        'left_front_wheel_condition',
        'rear_right_wheel_condition',
        'rear_left_wheel_condition',
        'comment',
    ];

    /**
     * La relación entre los moviemientos del vehículo y las ordenes de
     * trabajo, que es donde se sabe que vehículo es, uno a muchos
     */
    public function workOrder()
    {
        return $this->belongsTo('sanoha\Models\WorkOrder');
    }

    /**
     * La relación entre los movimientos del vehículo y usuarios (que son
     * quienes registran los movimientos), uno a muchos.
     */
    public function registeredBy()
    {
        return $this->belongsTo('sanoha\Models\User', 'registered_by');
    }
}
