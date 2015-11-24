<?php
namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class WorkOrderFormRequest extends Request
{
	/**
	 * Reglas de validación para la creaciòn de la orden de trabajo
	 */
	protected $workOrderRules = [
		'vehicle_responsable'   =>  'required|exists:employees,id,authorized_to_drive_vehicles,1',
        'vehicle_id'            =>  'required|exists:vehicles,id',
        'destination'           =>  'required|text',
        'internal_accompanists' =>  'array|exists:employees,id',
        'external_accompanists' =>  'array|array_data:alpha_numeric_spaces',
        'work_description'      =>  'required|text'
	];

	/**
	 * La reglas de validación para la creación y edición del reporte principal y
	 * reporte de acompañante interno de la orden de trabajo
	 */
	protected $workOrderReportRules = [
		'work_order_report'		=>	'required'
	];

	/**
	 * Las reglas de validacón para el registro de entrada/salida de vehículos
	 */
	protected $vehicleMovementRules = [
		'mileage'                       =>  'required|numeric',
        'fuel_level'                    =>  'required|text',
        'internal_cleanliness'          =>  'required|alpha',
        'external_cleanliness'          =>  'required|alpha',
        'paint_condition'               =>  'required|alpha',
        'bodywork_condition'            =>  'required|alpha',
        'right_front_wheel_condition'   =>  'required|alpha',
        'left_front_wheel_condition'    =>  'required|alpha',
        'rear_right_wheel_condition'    =>  'required|alpha',
        'rear_left_wheel_condition'     =>  'required|alpha',
        'comment'                       =>  'text'
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

       	if($this->route()->getName() == 'workOrder.mainReportUpdate' && ! \Auth::getUser()->can('workOrder.mainReportEdit'))
            return false;

       	if($this->route()->getName() == 'workOrder.internal_accompanist_report_store' && (! \Auth::getUser()->can('workOrder.internal_accompanist_report_edit_form') || ! \Auth::getUser()->can('workOrder.internal_accompanist_report_form')))
            return false;
        
       	if($this->route()->getName() == 'workOrder.vehicleMovementStore' && ! \Auth::getUser()->can('workOrder.vehicleMovementForm'))
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
		if($this->route()->getName() == 'workOrder.vehicleMovementStore')
			return $this->vehicleMovementRules;

		if($this->route()->getName() == 'workOrder.update' || $this->route()->getName() == 'workOrder.store')
			return $this->workOrderRules;

		if($this->route()->getName() == 'workOrder.internal_accompanist_report_store' || $this->route()->getName() == 'workOrder.mainReportUpdate')
			return $this->workOrderReportRules;
	}
	
	public function messages()
	{
		return [
			'vehicle_responsable.exists'   		=>  'No se encontró la información del responsable del vehículo.',
			'vehicle_responsable.required'		=>	'Debes elegir al empleado responsable del vehículo.',
			
            'vehicle_id.exists'        			=>  'El vehículo no existe en la base de datos.',
            'vehicle_id.required'				=>	'El vehículo es un campo obligatorio, elige uno.',
            
            'destination.text'         			=>  'El destino tiene un formato inválido, sólo se permiten letras y/o números.',
            'destination.required'				=>	'Digita donde se realizarán las actividades de la orden de trabajo.',
            
            'internal_accompanists.exists'		=>  'No se encontraró el empleado en la base de datos.',

            'external_accompanists.array_data'	=>	'El acompañante externo tiene un formato inválido, sólo se permiten letras, espacios y/o números.',
            
            'work_description.text'    			=>  'Sólo se permiten letras, números, espacios, puntos, guiones y/o arroba.',
            'work_description.required'			=>	'Debes digitar una descripción para la orden de trabajo.'
		];
	}

}
