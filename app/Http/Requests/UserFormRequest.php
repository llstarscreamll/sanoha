<?php namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class UserFormRequest extends Request
{
    protected $rules = [
        'name'          =>  'required|min:3|max:50|alpha_spaces',
        'lastname'      =>  'min:3|max:50|alpha_spaces',
        'email'         =>  'required|email|unique:users,email',
        'role_id'       =>  'exists:roles,id',
        'password'      =>  'required|confirmed|min:6',
        'activated'     =>  'boolean'
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route()->getName() == 'users.update' && ! \Auth::getUser()->can('users.edit'))
            return false;
        
        if($this->route()->getName() == 'users.store' && ! \Auth::getUser()->can('users.create'))
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
        
        if ($this->route()->getName() == 'users.update') {
            $rules['email']     = 'required|email|unique:users,email,' . $this->route()->getParameter('users');
            $rules['password']  = 'confirmed|min:6';
        }
        
        if($this->route()->getName() == 'users.index')
            $rules = [
                'find'  =>  'text' // custom rule declared on sanoha\Provides\CustomValidatorServiceProvider
                ];

        return $rules;
    }
    
    public function messages()
    {
        return [
            'email.email'           =>  'La dirección de correo electrónico no es válida.',
            'email.required'        =>  'La dirección de correo electrónico es oblogatoria.',
            'email.unique'          =>  'La dirección de correo ya ha sido registrada.',
            
            'find.text'             =>  'Formato incorrecto, solo se permiten letras, números, espacios, guiones y/o arroba (@).',

            'lastname.alpha_spaces' =>  'El apellido sólo puede contener letras y/o espacios.',
            'lastname.min'          =>  'El apellido debe tener al menos :min caracteres.',
            'lastname.max'          =>  'El apellido debe tener máximo :max caracteres.',
            
            'name.alpha_spaces'     =>  'El nombre sólo puede contener letras y/o espacios.',
            'name.min'              =>  'El nombre debe tener al menos :min caracteres.',
            'name.max'              =>  'El nombre debe tener máximo :max caracteres.',
            'name.required'         =>  'El nombre es un campo obligatorio.',

            'password.confirmed'    =>  'Las contraseñas no coinciden.',
            'password.min'          =>  'La contraseña debe tener al menos :min caracteres.',
            'password.required'     =>  'Por favor digita una contraseña.',

            'role_id.exists'        =>  'Tipo de usuario inválido.',
            
            'activated.boolean'     =>  'El estado tiene un formato incorrecto.',
        ];
    }
}