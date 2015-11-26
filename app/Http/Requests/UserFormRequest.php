<?php
namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class UserFormRequest extends Request
{
    protected $rules = [
        'name'              =>  'required|min:3|max:50|alpha_spaces',
        'lastname'          =>  'min:3|max:50|alpha_spaces',
        'email'             =>  'required|email|unique:users,email',
        'role_id'           =>  'exists:roles,id',
        'sub_cost_center_id'=>  'exists:sub_cost_centers,id',
        'area_id'           =>  'numeric|exists:areas,id',
        'area_chief'        =>  'boolean',
        'employee_id'       =>  'exists:employees,id',
        'activated'         =>  'boolean',
        'password'          =>  'required|confirmed|min:6',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route()->getName() == 'users.update' && ! \Auth::getUser()->can('users.edit')) {
            return false;
        }
        
        if ($this->route()->getName() == 'users.store' && ! \Auth::getUser()->can('users.create')) {
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
        $rules = $this->rules;
        
        if ($this->route()->getName() == 'users.update') {
            $rules['email']     = 'required|email|unique:users,email,' . $this->route()->getParameter('users');
            $rules['password']  = 'confirmed|min:6';
        }
        
        if ($this->route()->getName() == 'users.index') {
            $rules = [
                'find'  =>  'text' // custom rule declared on sanoha\Provides\CustomValidatorServiceProvider
                ];
        }

        return $rules;
    }
    
    public function messages()
    {
        return [
            'name.alpha_spaces'         =>  'El nombre sólo puede contener letras y/o espacios.',
            'name.min'                  =>  'El nombre debe tener al menos :min caracteres.',
            'name.max'                  =>  'El nombre debe tener máximo :max caracteres.',
            'name.required'             =>  'El nombre es un campo obligatorio.',
            
            'lastname.alpha_spaces'     =>  'El apellido sólo puede contener letras y/o espacios.',
            'lastname.min'              =>  'El apellido debe tener al menos :min caracteres.',
            'lastname.max'              =>  'El apellido debe tener máximo :max caracteres.',

            'email.email'               =>  'La dirección de correo electrónico no es válida.',
            'email.required'            =>  'La dirección de correo electrónico es oblogatoria.',
            'email.unique'              =>  'La dirección de correo ya ha sido registrada.',

            'role_id.exists'            =>  'Tipo de usuario inválido.',
            
            'sub_cost_center_id.exists' =>  'No se encontró información del centro de costo.',
            
            'area_id.numeric'           =>  'El área tiene un formato inválido.',
            'area_id.exists'            =>  'No se encontró el área seleccionada.',
            
            'area_chief.boolean'        =>  'El formato es inválido.',
            
            'employee_id.exists'        =>  'No se encontró información del empleado.',
            
            'activated.boolean'         =>  'El estado tiene un formato incorrecto.',

            'password.confirmed'        =>  'Las contraseñas no coinciden.',
            'password.min'              =>  'La contraseña debe tener al menos :min caracteres.',
            'password.required'         =>  'Por favor digita una contraseña.',
            
            'find.text'                 =>  'Formato incorrecto, solo se permiten letras, números, espacios, guiones y/o arroba (@).',
        ];
    }
}
