<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalAccompanist extends Model {

	protected $dates = ['created_at', 'updated_at'];
	
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'external_accompanists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['work_order_id', 'fullname'];
    
    /**
     * La relación entre acompañantes externos y odenes de trabajo, uno a muchos
     */
    public function workOrder()
    {
        return $this->belongsTo('sanoha\Models\WorkOrder');
    }
}
