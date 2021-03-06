<?php
namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class ActivityReportFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route()->getName() == 'activityReport.update' && ! \Auth::getUser()->can('activityReport.edit')) {
            return false;
        }
        
        if ($this->route()->getName() == 'activityReport.store' && ! \Auth::getUser()->can('activityReport.create')) {
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // la actividad minera elegida por el usuario
        $mining_activity    = \sanoha\Models\MiningActivity::where('id', '=', $this->input('mining_activity_id'))->get()->first();

        // Se alarga la restricción por unos días mientras se ponen al tanto con los reportes
        // las fechas entre las que se debe reportar la actividad minera
        $date_after    = \Carbon\Carbon::now()->subDays(30)->toDateString();
        $date_before    = \Carbon\Carbon::now()->addDays(5)->toDateString();
        
        // el nombre de la ruta donde nos encontramos
        $current_route    = $this->route()->getName();
        
        // la reglas de validación
        $rules = [];
        
        // si la ruta es para actualizar o crear un registro, las reglas serán unas
        if ($current_route == 'activityReport.store' || $current_route == 'activityReport.update') {
            $rules = [
                'employee_id'       =>  'required|numeric|exists:employees,id',
                'mining_activity_id'=>  'required|numeric|exists:mining_activities,id',
                'quantity'          =>  'numeric',
                'reported_at'       =>  'required|date|after:'.$date_after.'|before:'.$date_before,
                'comment'           =>  'alpha_spaces'
            ];

            if ($mining_activity) {
                $rules['quantity'] = 'required|numeric|between:0.1,' . $mining_activity->maximum;
            }
            
            // en la actualización de registros no se tiene en cuenta las restricciones de fecha
            if ($current_route == 'activityReport.update') {
                $rules['reported_at']        =        'required|date';
            }
        }
        
        // si la ruta es para alguno de los reportes, las reglas serán otras
        if ($current_route == 'activityReport.index' || $current_route == 'activityReport.calendar' || $current_route == 'activityReport.individual') {
            $rules = [
                'from'  =>  'date',
                'to'    =>  'date',
                'find'  =>  'alpha_numeric_spaces'
            ];
        }

        // las reglas para el nuevo formulario
        if ($current_route == 'activityReport.newStore'){
            $rules = [
                'employee_id'           =>  'required|numeric|exists:employees,id',
                'mining_activity'       =>  'array',
                'mining_activity_price' =>  'array',
                'reported_at'           =>  'required|date|after:'.$date_after.'|before:'.$date_before,
                'comment'               =>  'alpha_spaces'
            ];
        }
        
        return $rules;
    }
    
    /**
     * Mensajes de error para la validación
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'employee_id.exists'            =>        'Trabajador inválido.',
            'employee_id.numeric'           =>        'Identificador de empleado inválido.',
            'employee_id.required'          =>        'Selecciona un trabajador de la lista.',
            
            'mining_activity_id.numeric'    =>        'Identificador de labor minera inválido.',
            'mining_activity_id.exists'     =>        'Labor minera inválida.',
            'mining_activity_id.required'   =>        'Debes seleccionar la actividad que hizo el trabajador.',
            
            'quantity.required'             =>        'Debes digitar la cantidad.',
            'quantity.numeric'              =>        'La cantidad debe tener formato numérico.',
            'quantity.between'              =>        'El rango permitido es entre :min y :max.',
            
            'reported_at.required'          =>        'Selecciona la fecha en que fue realizada la actividad.',
            'reported_at.date'              =>        'La fecha tiene un formato inválido.',
            'reported_at.before'            =>        'La fecha debe ser antes del :date.',
            'reported_at.after'             =>        'La fecha debe ser depúes del :date.',
            
            'comment.alpha_spaces'          =>        'El comentario sólo debe contener letras y/o espacios.',
            
            /* mensajes para index */
            'from.date'                     =>        'La fecha de inicio del filtro tiene un formato inválido.',
            
            'find.alpha_numeric_spaces'     =>        'Sólo puedes digitar letras, números y/o espacios.',
            
            'to.date'                       =>        'La fecha de fin del filtro tiene un formato inválido.',
            'to.after'                      =>        'La fecha de fin debe ser mas reciente que la de inicio.'
        ];
    }
}
