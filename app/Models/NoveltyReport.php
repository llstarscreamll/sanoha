<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;

class NoveltyReport extends Model
{
	use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'novelty_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    /**
     * 
     */ 
    public function novelty()
    {
        return $this->belongsTo('\sanoha\Models\Novelty');
    }
    
    /**
     * 
     */
    public function employee()
    {
        return $this->belongsTo('\sanoha\Models\Employee');
    }
    
}
