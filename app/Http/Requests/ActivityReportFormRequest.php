<?php namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class ActivityReportFormRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$costCenter 		= \Session::get('current_cost_center_id');
		$mining_activity 	= \sanoha\Models\MiningActivity::where('id', '=', $this->input('mining_activity_id'))->get()->first();
		
		$date_after = \Carbon\Carbon::now()->subDays(2)->toDateString();
		$date_before = \Carbon\Carbon::now()->addDays(1)->toDateString();
		
		$rules 				= [];
		
		if($this->route()->getName() == 'activityReport.store'){
			
			$rules = [
				'employee_id'				=>		'required|numeric|exists:employees,id',//,cost_center_id,' . $costCenter,
				'mining_activity_id'		=>		'required|numeric|exists:mining_activities,id',
				'quantity' 					=>		'required|numeric',
				'reported_at'				=>		'required|date|after:'.$date_after.'|before:'.$date_before,
				'comment'					=>		'alpha_spaces'
			];

			if($mining_activity){
				$rules['quantity']			=		'required|numeric|between:1,' . $mining_activity->maximum;
			}
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
			'employee_id.exists'		=>		'Trabajador inválido.',
			'employee_id.numeric'		=>		'Identificador de empleado inválido.',
			
			'mining_activity_id.numeric'=>		'Identificador de labor minera inválido.',
			'mining_activity_id.exists'	=>		'Labor minera inválida.',
			
			'quantity.required'			=>		'Debes digitar la cantidad.',
			'quantity.between'			=>		'El rango permitido es entre :min y :max.',
			
			'comment.alpha_spaces'		=>		'El comentario sólo debe contener letras y/o espacios.'
		];
	}

}
