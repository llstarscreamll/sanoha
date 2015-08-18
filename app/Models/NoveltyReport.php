<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoveltyReport extends Model
{
	use SoftDeletes;

    protected $dates = ['reported_at', 'created_at', 'updated_at', 'deleted_at'];

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
    protected $fillable = ['sub_cost_center_id', 'employee_id', 'novelty_id', 'comment', 'reported_at'];
    
    /**
     * 
     */ 
    public function subCostCenter()
    {
        return $this->belongsTo('\sanoha\Models\SubCostCenter');
    }
    
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
