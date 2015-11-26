<?php
namespace sanoha\Http\Requests;

use sanoha\Http\Requests\Request;

class RoleFormRequest extends Request
{
    /**
     * Validation rules
     * 
     * @var array
     */
    protected $rules = [
        'name'          =>  'required|min:3|max:50|alpha_dots|unique:roles,name',
        'display_name'  =>  'required|min:3|max:50|alpha_spaces',
        'description'   =>  'alpha_spaces|min:5|max:150'
    ];
    
    /**
     * Custom error messages
     * 
     * @var array
     */
    protected $messages = [
        'name.required'             =>  'El nombre es un campo obligatorio.',
        'name.min'                  =>  'El nombre debe tener al menos :min caracteres.',
        'name.max'                  =>  'El nombre debe tener máximo :max caracteres.',
        'name.alpha_dots'           =>  'El nombre sólo puede contener letras sin tildes y/o puntos.',
        'name.unique'               =>  'Ya existe un rol con este nombre.',

        'display_name.required'     =>  'Digita un alias por favor.',
        'display_name.min'          =>  'El alias debe tener al menos :min caracteres.',
        'display_name.max'          =>  'El alias debe tener máximo :max caracteres.',
        'display_name.alpha_spaces' =>  'El alias sólo puede contener letras y/o espacios.',

        'description.alpha_spaces'  =>  'La descripción debe estar compuesta por letras y/o espacios.',
        'description.min'           =>  'La descripción debe tener al menos :min caracteres.',
        'description.max'           =>  'La descripción debe tener máximo :max caracteres.',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route()->getName() == 'roles.update' && ! \Auth::getUser()->can('roles.edit')) {
            return false;
        }
        
        if ($this->route()->getName() == 'roles.store' && ! \Auth::getUser()->can('roles.create')) {
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

        if ($this->route()->getName() == 'roles.update') {
            $rules['name'] = 'required|min:3|max:50|alpha_dots|unique:roles,name,'. $this->route()->getParameter('roles');
        }

        return $rules;
    }
    
    /**
     * Custom error messages for validation
     * 
     * @return array
     */
    public function messages()
    {
        $messages = $this->messages;
        
        return $messages;
    }
}
