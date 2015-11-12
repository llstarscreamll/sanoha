<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{

    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'work_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'authorized_by',
        'vehicle_id',
        'vehicle_responsable',
        'destination',
        'work_description'
    ];

    /**
     * La relación entre las ordenes de trabajo y los movimientos del vehículo
     * (entradas y salidas), uno a muchos.
     */
    public function vehicleMovements()
    {
        return $this->hasMany('sanoha\Models\VehicleMovement');
    }
    
    /**
     * La relación enter la orden de trabajo y los reportes de la misma, uno a muchos
     */
    public function workOrderReports()
    {
        return $this->hasMany('sanoha\Models\WorkOrderReport');
    }
    
    /**
     * La relación entre ordenes de trabajo y acopañantes internos (tabla empleados),
     * muchos a muchos
     */
    public function internalAccompanists()
    {
        return $this->belongsToMany('sanoha\Models\Employee', 'internal_accompanists')
            ->withPivot('work_report', 'reported_by', 'reported_at');
    }
    
    /**
     * La relación entre ordenes de trabajo y acompañantes externos, uno a muchos
     */
    public function externalAccompanists()
    {
        return $this->hasMany('sanoha\Models\ExternalAccompanist');
    }
    
    /**
     * La relación entre ordenes de trabajo y vehículos, uno a muchos
     */
    public function vehicle()
    {
        return $this->belongsTo('sanoha\Models\Vehicle');
    }
    
    /**
     * La relación entre roden de trabajo y empleado (responsable de vehiculo),
     * uno a muchos
     */
    public function employee()
    {
        return $this->belongsTo('sanoha\Models\Employee', 'vehicle_responsable');
    }
    
    /**
     * La relación entre ordene de trabajo y usuario (quien autoriza), uno a muchos
     */
    public function user()
    {
        return $this->belongsTo('sanoha\Models\User', 'authorized_by');
    }
    
    /**
     * La relación entre ordenes de trabajo y acopañantes internos (tabla empleados),
     * muchos a muchos
     */
    public function getInternalAccompanistsReportedBy($id)
    {
        return \sanoha\Models\User::find($id)->fullname;
    }

    /**
     * Determina si hay o no salidas o entradas registradas del vehículo
     * de la orden de trabajo en cuestión
     *
     * @param $action string
     * @return bool
     */
    public function hasVehicleMovement($action)
    {
        if ($this->vehicleMovements) {
            foreach ($this->vehicleMovements as $key => $movement) {
                if ($movement->action == $action) {
                    return true;
                }
            }
        }
        return false;
    }
}
