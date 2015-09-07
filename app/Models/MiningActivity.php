<?php namespace sanoha\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MiningActivity extends Model
{
	use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mining_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'short_name'];
    
    /**
     * 
     */ 
    public function activityReport()
    {
        return $this->hasMany('\sanoha\Models\ActivityReport');
    }
    
    /**
     * 
     */
    public function getNameAndAbbreviationAttribute()
    {
        return $this->attributes['name'] . ' | ' . $this->attributes['short_name'];
    }

    /**
     * Obtiene las actividades mineras en un orden específico
     * 
     * @retunr  Collection
     */
    public static function customOrder()
    {
        $labors = \sanoha\Models\MiningActivity::all();
        $order = [
            1  =>  'VC',
            2  =>  'VR',
            3  =>  'PA',
            4  =>  'PRG',
            5  =>  'PRI',
            6  =>  'TAC',
            7  =>  'TRM',
            8  =>  'MLC',
            9  =>  'BENDA',
            10  =>  'EVS',
            11  =>  'ADM',
            12  =>  'CRG',
            13  =>  'CDS',
            14  =>  'CNST',
            15  =>  'MTA',
            16  =>  'MTDCH',
            17  =>  'MTDPT',
            18  =>  'MAP',
            19  =>  'MAD',
            20  =>  'OTROS'
        ];
        $data = array();
        
        foreach ($labors as $labor) {
            
            foreach ($order as $key => $value) {
            
                if($value === $labor->short_name){
                    $data[$key] = [
                        'id'                    =>  $labor->id,
                        'name'                  =>  $labor->name,
                        'short_name'            =>  $labor->short_name,
                        'maximum'               =>  $labor->maximum,
                        'nameAndAbbreviation'   =>  $labor->nameAndAbbreviation,
                    ];
                }
            }
        }
        
        ksort($data);

        return $data;
    }
}
