<?php namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class WorkOrderFormRequest extends Request
{
	/**
	 * Reglas de validación
	 */
	protected $rules = [
		'vehicle_responsable'   =>  'required|exists:employees,id,authorized_to_drive_vehicles,1',
        'vehicle_id'            =>  'required|exists:vehicles,id',
        'destination'           =>  'required|text',
        'internal_accompanists' =>  'array|exists:employees,id',
        'external_accompanists' =>  'array|array_data:alpha_numeric_spaces',
        'work_description'      =>  'required|text'
	];

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if($this->route()->getName() == 'workOrder.update' && ! \Auth::getUser()->can('workOrder.edit'))
            return false;
        
        if($this->route()->getName() == 'workOrder.store' && ! \Auth::getUser()->can('workOrder.create'))
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
		return $this->rules;
	}
	
	public function messages()
	{
		return [
			'vehicle_responsable.exists'   	=>  'No se encontró la información del responsable del vehículo.',
			'vehicle_responsable.required'	=>	'Debes elegir al empleado responsable del vehículo.',
			
            'vehicle_id.exists'        		=>  'El vehículo no existe en la base de datos.',
            'vehicle_id.required'			=>	'El vehículo es un campo obligatorio, elige uno.',
            
            'destination.text'         		=>  'El destino tiene un formato inválido, sólo se permiten letras y/o números.',
            'destination.required'			=>	'Digita donde se realizarán las actividades de la orden de trabajo.',
            
            'internal_accompanists.exists'	=>  'No se encontraró el empleado en la base de datos.',

            'external_accompanists.array_data'=>'El acompañante externo tiene un formato inválido, sólo se permiten letras, espacios y/o números.',
            
            'work_description.text'    		=>  'Sólo se permiten letras, números, espacios, puntos, guiones y/o arroba.',
            'work_description.required'		=>	'Debes digitar una descripción para la orden de trabajo.'
		];
	}

}
