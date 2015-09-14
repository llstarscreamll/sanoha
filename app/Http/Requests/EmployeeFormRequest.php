<?php

namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class EmployeeFormRequest extends Request
{
    
    protected $rules = [
        'name'                          =>  'required|min:3|max:50|alpha_spaces',
        'lastname'                      =>  'required|min:3|max:50|alpha_spaces',
        'identification_number'         =>  'required|numeric|unique:employees,identification_number',
        'email'                         =>  'email|unique:employees,email',
        'sub_cost_center_id'            =>  'numeric|exists:sub_cost_centers,id',
        'position_id'                   =>  'numeric|exists:positions,id'
    ];
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route()->getName() == 'employee.update' && ! \Auth::getUser()->can('employee.edit'))
            return false;
        
        if($this->route()->getName() == 'employee.store' && ! \Auth::getUser()->can('employee.create'))
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
        $rules = $this->rules;
        
        if ($this->route()->getName() == 'employee.update') {
            $rules['email']                 = 'email|unique:employees,email,' . $this->route()->getParameter('employee');
            $rules['identification_number'] = 'required|numeric|unique:employees,identification_number,' . $this->route()->getParameter('employee');
        }
        
        if($this->route()->getName() == 'employee.index')
            $rules = [
                'find'  =>  'text' // custom rule declared on sanoha\Provides\CustomValidatorServiceProvider
                ];

        return $rules;
    }
    
    public function messages()
    {
        return [
            'name.alpha_spaces'     =>  'El nombre sólo puede contener letras y/o espacios.',
            'name.required'         =>  'El nombre es un campo obligatorio.',
            'name.min'              =>  'El nombre debe tener al menos :min caracteres.',
            'name.max'              =>  'El nombre debe tener máximo :max caracteres.',
            
            'lastname.required'     =>  'El apellido es un campo obligatorio.',
            'lastname.alpha_spaces' =>  'El apellido sólo puede contener letras y/o espacios.',
            'lastname.min'          =>  'El apellido debe tener al menos :min caracteres.',
            'lastname.max'          =>  'El apellido debe tener máximo :max caracteres.',
            
            'identification_number.required'=>  'El número de identificación es un campo obligatorio.',
            'identification_number.unique'  =>  'Éste número de identificación ya está registrado.',
            'identification_number.numeric' =>  'El número de identificación tiene un formato incorrecto, sólo se permiten números.',

            'email.email'           =>  'La dirección de correo electrónico no es válida.',
            'email.unique'          =>  'La dirección de correo ya ha sido registrada.',

            'sub_cost_center_id.exists' =>  'El centro de costo no existe.',
            'sub_cost_center_id.numeric'=>  'El centro de costo tiene formato inválido.',
            
            'position_id.exists'    =>  'El cargo no existe.',
            'position_id.numeric'   =>  'El cargo tiene formato inválido.'
        ];
    }
}
