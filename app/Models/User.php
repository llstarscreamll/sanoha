<?php
namespace sanoha\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use SoftDeletes;
    use EntrustUserTrait;
    use Authenticatable, CanResetPassword;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'users';

    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'area_id',
        'area_chief',
        'name',
        'lastname',
        'email',
        'password',
        'activated'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * La relación entre empleados y usuarios, es decir, los dueños de la
     * información de los empleados quienes pueden editar o crear
     * información que afecta a los empleados asignados en
     * determinados módulos del sistema, muchos a muchos
     */
    public function employees()
    {
        return $this->belongsToMany('sanoha\Models\Employee', 'employee_owners');
    }
    
    /**
     * La relación entre usuario y area, uno a muchos
     */
    public function area()
    {
        return $this->belongsTo('sanoha\Models\Area');
    }
    
    /**
     * La relción entre los usuarios y los subcentros de costo, muchos a muchos
     */
    public function subCostCenters()
    {
        return $this->belongsToMany('sanoha\Models\SubCostCenter', 'sub_cost_center_owner');
    }
    
    /**
     * Obtiene los centros de costo que tiene asociados el usuario a traves de los subcentros de costo
     * 
     * @return array
     */
    public function getCostCentersArray()
    {
        $costCenters = [];
        foreach ($this->subCostCenters as $subCostCenter) {
            $costCenters[$subCostCenter->CostCenter->id]['id'] = $subCostCenter->CostCenter->id;
            $costCenters[$subCostCenter->CostCenter->id]['name'] = $subCostCenter->CostCenter->name;
        }
        
        return $costCenters;
    }
    
    /**
     * Comprueba si el usuario tiene asignado el centro de costo dado
     * 
     * @param integer $costCenterId
     * @return bool
     */
    public function hasSubCostCenter($subCostCenterId)
    {
        return in_array($subCostCenter, $this->subCostCenters->lists('id')->toArray());
    }

    /**
     * Verifica si el usuario tiene X centro de costo asociado
     * 
     * @param integer $costCenterId
     * @return bool
     */
    public function hasCostCenter($costCenterId)
    {
        return array_key_exists($costCenterId, $this->getCostCentersArray());
    }

    /**
     * Get the user roles display_name attribute
     * 
     * @return string
     */
    public function getRoles()
    {
        $roles = '';

        foreach ($this->roles as $role) {
            $roles .= $role->display_name.' ';
        }

        return $roles;
    }

    /**
     * Get the user roles id
     * 
     * @return array
     */
    public function getIdRoles()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->id;
        }
        return $roles;
    }
    
    /**
     * Obtiene los id's de los empleados relacionados al empleado
     * 
     * @return array
     */
    public function getIdEmployees()
    {
        $employees = [];
        foreach ($this->employees as $employee) {
            $employees[] = $employee->id;
        }
        return $employees;
    }
    
    /**
     * Get the user sub cost centers name attribute
     * 
     * @return string
     */
    public function getSubCostCenters()
    {
        $subCostCenters = '';
        
        foreach ($this->subCostCenters as $center) {
            $subCostCenters .= $center->name.' ';
        }
        return $subCostCenters;
    }
    
    /**
     * Obtiene los empleados asociados al usuario
     * 
     * @return string
     */
    public function getEmployees()
    {
        $employees = '';
        
        foreach ($this->employees as $employee) {
            $employees .= $employee->fullname.' ';
        }
        return $employees;
    }
    
    /**
     * Comprueba si el usuario tiene asociado al empleado dado
     * 
     * @param string $costCenterId
     * @return bool
     */
    public function hasEmployee($employee_id)
    {
        return in_array($employee_id, $this->employees->lists('id')->toArray());
    }
    
    /**
     * Get the user sub cost centers id
     * 
     * @return array
     */
    public function getSubCostCentersId()
    {
        $costCenters = [];
        foreach ($this->subCostCenters as $center) {
            $costCenters[] = $center->id;
        }
        return $costCenters;
    }
    
    /**
     * Getter, concatena el nombre y el apellido del empleado
     * 
     * @return string
     */
    public function getFullnameAttribute()
    {
        return $this->lastname . ' ' . $this->name;
    }
    
    /**
     * Search by name query scope
     * 
     * @return object
     */
    public function scopeSearchByName($query, $name)
    {
        if (!empty(trim($name))) {
            return $query->where('name', 'LIKE', '%'.$name.'%');
        }
    }
    
    /**
     * Search by lastname query scope
     * 
     * @return object
     */
    public function scopeOrSearchByLastname($query, $last_name)
    {
        if (!empty(trim($last_name))) {
            return $query->orWhere('lastname', 'LIKE', '%'.$last_name.'%');
        }
    }

    /**
     * Search by email query scope
     * 
     * @return object
     */
    public function scopeOrSearchByEmail($query, $email)
    {
        if (!empty(trim($email))) {
            return $query->orWhere('email', 'LIKE', '%'.$email.'%');
        }
    }
    
    /**
     * The index data to display, and some filters if required
     * 
     * @param {string} $text
     * 
     * @return object Collection
     */
    public static function indexSearch($text)
    {
        return self::searchByName($text)
            ->orSearchByLastname($text)
            ->orSearchByEmail($text)
            ->where('id', '!=', \Auth::user()->id)
            ->with('roles')
            ->orderBy('updated_at', 'des')
            ->paginate(15);
    }
    
    /**
     * Devuelve el estado del empleado formateado en HTML y con algunas clases CSS
     * 
     * @return string
     */
    public function getHtmlActivatedState()
    {
        return $this->activated ? '<span class="text-success">Activado</span>' : '<span class="text-danger">Desactivado</span>';
    }
    
    /**
     * Obtiene el texto del estado del empleado para humanos, es decir activado/descartivado
     * en ves de 0/1 en la base de datos
     * 
     * @return string
     */
    public function getActivatedState()
    {
        return $this->activated ? 'Activado' : 'Desactivado';
    }
}
