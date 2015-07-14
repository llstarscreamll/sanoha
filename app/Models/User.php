<?php namespace sanoha\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;
    use SoftDeletes;
    use EntrustUserTrait;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'lastname', 'email', 'password', 'activated'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * 
     */
    public function costCenter()
    {
        return $this->belongsToMany('sanoha\Models\CostCenter', 'cost_center_owner');
    }
    /**
     * Comprueba si el usuario tiene asignado el centro de costo dado
     * 
     * @param   string  $costCenterId
     */
    public function hasCostCenter($costCenterId){
        
        foreach ($this->costCenter as $key => $costCenter) {
            
            if($costCenter->id === $costCenterId)
                return true;
                
        }
        
        return false;
    }

    /**
     * Hashing password
     * 
     * @param {string} $value
     * 
     * @return {string}
     */
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = \Hash::make($value);
    }

    /**
     * Get the user roles display_name attribute
     * 
     * @return {string}
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
     * @return {array}
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
     * Get the user cost centers name attribute
     * 
     * @return {string}
     */
    public function getCostCenters()
    {
        $costCenters = '';
        
        foreach ($this->costCenter as $center) {
            $costCenters .= $center->name.' ';
        }
        return $costCenters;
    }
    
    /**
     * Get the user cost centers id
     * 
     * @return {array}
     */
    public function getCostCentersId()
    {
        $costCenters = [];
        foreach ($this->costCenter as $center) {
            $costCenters[] = $center->id;
        }
        return $costCenters;
    }
    
    /**
     * 
     */ 
    public function getFullName()
    {
        return $this->name . ' ' . $this->lastname;
    }
    
    /**
     * Search by name query scope
     * 
     * @return {object}
     */
    public function scopeSearchByName($query, $name)
    {
        if(!empty( trim($name) ))
            return $query->where('name', 'LIKE', '%'.$name.'%');
    }
    
    /**
     * Search by lastname query scope
     * 
     * @return {object}
     */
    public function scopeOrSearchByLastname($query, $last_name)
    {
        if(!empty( trim($last_name) ))
            return $query->orWhere('lastname', 'LIKE', '%'.$last_name.'%');
    }

    /**
     * Search by email query scope
     * 
     * @return {object}
     */
    public function scopeOrSearchByEmail($query, $email)
    {
        if(!empty( trim($email) ))
            return $query->orWhere('email', 'LIKE', '%'.$email.'%');
    }
    
    /**
     * The index data to display, and some filters if required
     * 
     * @param {string} $text
     * 
     * @return {object} Collection
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
     * 
     */
    public function getHtmlActivatedState()
    {
        return $this->activated ? '<span class="text-success">Activado</span>' : '<span class="text-danger">Desactivado</span>';
    }
    
    /**
     * 
     */
    public function getActivatedState()
    {
        return $this->activated ? 'Activado' : 'Desactivado';
    }
    
}
