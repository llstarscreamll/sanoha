<?php
namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model {

	protected $dates = ['created_at', 'updated_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'areas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'short_name'];
    
    /**
     * La relación entre areas y usuarios, uno a muchos
     */
    public function users()
    {
        return $this->hasMany('sanoha\Models\User');
    }

}
