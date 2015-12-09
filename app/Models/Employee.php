<?php
namespace sanoha\Models;

use Spatie\Activitylog\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogsActivityInterface;

class Employee extends Model implements LogsActivityInterface
{
    use SoftDeletes;
    use LogsActivity;

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
    protected $fillable = [
        'position_id',
        'sub_cost_center_id',
        'identification_number',
        'name',
        'lastname',
        'email',
        'phone',
        'authorized_to_drive_vehicles',
    ];
    
    /**
     * Get the message that needs to be logged for the given event name.
     *
     * @param string $eventName
     * @return string
     */
    public function getActivityDescriptionForEvent($eventName)
    {
        $data = [
            'nombre_centro_costo'   =>  $this->subCostCenter->costCenter->name,
            'nombre_sub_centro'     =>  $this->subCostCenter->name,
            'nombres_empleado'      =>  $this->fullname,
            'numero_identificacion' =>  $this->identification_number,
            'nombre_cargo'          =>  $this->position->name,
            
            'id_centro_costo'       =>  $this->subCostCenter->costCenter->id,
            'id_sub_centro_costo'   =>  $this->sub_cost_center_id,
            'id_empleado'           =>  $this->id,
            'id_cargo'              =>  $this->position_id,
        ];
        
        if ($eventName == 'created') {
            return '<strong>@user</strong> creó un empleado, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|Employee' // de qué modulo
                    .'|create' // la acción
                    .'|employee.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|success' // la clase css que tendrá este registro
                    ;
        }
    
        if ($eventName == 'updated') {
            return '<strong>@user</strong> actualizó la información de un empleado, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|Employee' // de qué modulo
                    .'|update' // la acción
                    .'|employee.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|warning' // la clase css que tendrá este registro
                    ;
        }
    
        if ($eventName == 'deleted') {
            return '<strong>@user</strong> eliminó la información de un empleado, código "<strong>'.$this->id.'</strong>"' // lo que se hizo
                    .'|Employee' // de qué modulo
                    .'|delete' // la acción
                    .'|employee.show' // link de acceso a los detalles
                    .'|'.$this->id // el id del registro
                    .'|'.json_encode($data, JSON_PRETTY_PRINT) // los datos
                    .'|danger' // la clase css que tendrá este registro
                    ;
        }
    
        return '';
    }
    
    /**
     * La relación entre empleados y usuarios, muchos a muchos
     */
    public function users()
    {
        return $this->belongsToMany('sanoha\Models\User', 'employee_owners');
    }
    
    /**
     * La relación entre un empledo y las ordenes de trabajo, muchos a muchos
     */
    public function internalAccompanists()
    {
        return $this->belongsToMany('sanoha\Models\WorkOrder', 'internal_accompanists')
            ->withPivot('work_report', 'reported_by', 'reported_at');
    }
    
    /**
     * La relación entre el empleado y el subcentro de costo, uno a muchos
     */
    public function subCostCenter()
    {
        return $this->belongsTo('sanoha\Models\SubCostCenter');
    }
    
    /**
     * La relación entre empleado y el cargo, uno a muchos
     */
    public function position()
    {
        return $this->belongsTo('sanoha\Models\Position');
    }
    
    /**
     * La relación entre empleado y novedades, uno a muchos
     */
    public function noveltyReport()
    {
        return $this->hasMany('sanoha\Models\NoveltyReport');
    }
    
    /**
     * La relación entre empleado y reportes de actividades mineras, uno a muchos
     */
    public function activityReport()
    {
        return $this->hasMany('sanoha\Models\ActivityReport');
    }
    
    /**
     * Obtiene el nombre completo del empleado, primero el apellido, seguido el nombre
     * 
     * @return string
     */
    public function getFullnameAttribute()
    {
        return ucwords(strtolower($this->attributes['lastname'] .' '. $this->attributes['name']));
    }
    
    /**
     * Obtengo el estado del empleado formateado, es decir con letras de cierto
     * color dependiendo del estado.
     * 
     * @return string
     */
    public function getStatusHtml()
    {
        // el valor a mostrar en la ui
        switch ($this->status) {
            case 'enabled':
                $txt = 'Activado';
                $style_class = 'text-success';
                break;
            case 'disabled':
                $txt = 'Desactivado';
                $style_class = 'text-danger';
                break;
            default:
                $txt = $this->status;
                $style_class = '';
                break;
        }
        
        $tag = '<span class="%style_class%">'.$txt.'</span>';
        
        return str_replace('%style_class%', $style_class, $tag);
    }
}
