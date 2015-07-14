<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;

class Novelty extends Model {
    
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'novelties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    
    /**
     * 
     */ 
    public function noveltyReport()
    {
        return $this->hasMany('\sanoha\Models\NoveltyReport');
    }

}
