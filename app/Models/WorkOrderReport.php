<?php
namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrderReport extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'work_order_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['work_order_id', 'work_order_report', 'reported_by'];
    
    /**
     * La relación entre el reporte de la orden de trabajo y la orden como tal,
     * uno a muchos
     */
    public function WorkOrder()
    {
        return $this->belongsTo('sanoha\Models\WorkOrder');
    }
    
    /**
     * La relación entre los reportes de la orden de trabajo y usuarios (quienes reportan)
     */
    public function reportedBy()
    {
        return $this->belongsTo('sanoha\Models\User', 'reported_by');
    }
}
