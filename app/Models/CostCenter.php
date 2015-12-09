<?php
namespace sanoha\Models;

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
    protected $fillable = ['name', 'short_name'];
    
    /**
     * La relación entre centros de costo y subcentros de costo, uno a muchos
     */
    public function subCostCenter()
    {
        return $this->hasMany('sanoha\Models\SubCostCenter');
    }
    
    /**
     * La relación entre centros de costo y empleados, uno a muchos
     */
    public function employees()
    {
        return $this->hasManyThrough('sanoha\Models\Employee', 'sanoha\Models\SubCostCenter');
    }
    
    /**
     * Obtengo una array de los centros de costo ordenados alfabéticamente junto
     * con sus respectivos sub centros de costo, para ser impresa en un select
     * 
     * @return  array
     */
    public static function getOrderListWithSubCostCenters()
    {
        $cost_centers = \sanoha\Models\CostCenter::orderBy('name')->get();
        $data = [];
        
        foreach ($cost_centers as $key => $cost_center) {
            foreach ($cost_center->subCostCenter as $sub_cost_center) {
                $data[$cost_center->name.' - '.$cost_center->short_name][$sub_cost_center->id] = $sub_cost_center->name.' - '.$sub_cost_center->short_name;
            }
        }
        
        return $data;
    }
}
