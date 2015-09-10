<?php namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class NovletyReportFormRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if($this->route()->getName() == 'noveltyReport.update' && ! \Auth::getUser()->can('noveltyReport.edit'))
            return false;
        
        if($this->route()->getName() == 'noveltyReport.store' && ! \Auth::getUser()->can('noveltyReport.create'))
            return false;
        
        return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [];
		$current_route 	= $this->route()->getName();
		$date_after 	= \Carbon\Carbon::now()->subDays(30)->toDateString();
		$date_before 	= \Carbon\Carbon::now()->addDays(5)->toDateString();
		
		if($current_route == 'noveltyReport.store' || $current_route == 'noveltyReport.update'){
			
			$rules = [
				'employee_id'				=>		'required|numeric|exists:employees,id',
				'novelty_id'				=>		'required|numeric|exists:novelties,id',
				'reported_at'				=>		'required|date|after:'.$date_after.'|before:'.$date_before,
				'comment'					=>		'alpha_spaces'
			];

		}
		
		if($current_route == 'noveltyReport.index' || $current_route == 'noveltyReport.calendar'){
			$rules = [
				'from'		=>	'date',
				'to'		=>	'date|after:from',
				'find'		=>	'alpha_numeric_spaces'
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
			'employee_id.exists'		=>		'El trabajador no existe, trabajador inválido.',
			'employee_id.numeric'		=>		'Identificador de empleado inválido.',
			'employee_id.required'		=>		'Selecciona un trabajador de la lista.',
			
			'novelty_id.numeric'		=>		'Identificador de tipo de novedad inválido.',
			'novelty_id.exists'			=>		'No existe el tipo novedad, novedad inválida.',
			'novelty_id.required'		=>		'Debes seleccionar la novedad que vas a reportar.',
			
			'reported_at.required'		=>		'Selecciona la fecha en que se presentó la novedad.',
			'reported_at.date'			=>		'La fecha tiene un formato inválido.',
			'reported_at.before'		=>		'La fecha debe ser de antes del :date.',
			'reported_at.after'			=>		'La fecha debe ser depúes del :date.',
			
			'comment.alpha_spaces'		=>		'El comentario sólo debe contener letras y/o espacios.',
			
			/* mensajes para index */
			'from.date'					=>		'La fecha de inicio del filtro tiene un formato inválido.',
			
			'find.alpha_numeric_spaces'	=>		'Sólo puedes digitar letras, números y/o espacios.',
			
			'to.date'					=>		'La fecha de fin del filtro tiene un formato inválido.',
			'to.after'					=>		'La fecha de fin debe ser mas reciente que la de inicio.'
		];
	}

}
