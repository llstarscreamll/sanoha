<?php
namespace sanoha\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    /**
     * The permissions categories
     * 
     * @var array
     */
    public $categories = [
        'role'              =>  'Roles',
        'user'              =>  'Usuarios',
        'employee'          =>  'Empleados',
        'noveltyReport'     =>  'Reporte de Novedades',
        'activityReport'    =>  'Reporte de Labores Mineras',
        'workOrder'         =>  'Ordenes de Trabajo',
        'log'               =>  'Log de Usuarios'
        ];
    
    /**
     * Dates on table
     * 
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description'];
    
    /**
     * Get ordered permissions by categories like role, user, etc...
     * 
     * @return array
     */
    public function getOrderedPermissions(array $permissions = [])
    {
        if(empty($permissions))
            $permissions = $this->orderBy('name')->get()->toArray();
        
        $orderedPermissions = array();

        foreach($permissions as $permission){
            
            foreach($this->categories as $key => $value){
                
                    if(strpos($permission['name'], $key) !== false){
                        $orderedPermissions[$key][] = $permission;
                }
            }
            
        }
        
        return $orderedPermissions;
    }
}
