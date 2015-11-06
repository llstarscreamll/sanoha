<?php   namespace sanoha\Providers;

use \Carbon\Carbon;

class CustomValidator extends \Illuminate\Validation\Validator
{
    /**
     * Añade la regla "array_data", valida que los datos dados en un array sean
     * los que se esperan según lo que se indique en $parameters, por ejemplo
     * $parameters[0] = numeric, validará que todos los valores del array
     * sean numéricos
     * 
     * @param   string  $attribute
     * @param   mixed   $string
     * @param   array   $parameters
     * 
     * @return  bool
     */
    public function validateArrayData($attribute, $value, $parameters)
    {
        // se verifica que se hayan dado cuando menos un parámetro
        $this->requireParameterCount(1, $parameters, 'array_data');
        
        // recorro los valores del array para validar cada uno
        foreach ($value as $key => $val) {
            // limpio los indicies vacíos que por alguna razón devuelve la función parseRule($rules)
            $rules = array_filter($this->parseRule($parameters));
            
            // valido el dato con las reglas que se hayan dado en los parámetros
            foreach ($rules as $ruleKey => $rule) {
                // si falla la validación, agrego el fallo de la regla y el mensaje de error a la colección
                if(! $this->{'validate'.$rule}($attribute, $val, [])){
                    $this->addFailure($attribute, $rule, []);
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Extend validation, add "alpha_spaces" rule, allow:
     * - alpha with accented characters (a-zA-ZÁÉÍÓÚáéíóú)
     * - spaces ( )
     *
     * @param   string  $attribute
     * @param   mixed   $string
     * @param   array   $parameters
     * 
     * @return  bool
     */ 
    public function validateAlphaSpaces($attribute, $value, $parameters)
    {
        return (bool) preg_match("/^[\p{L}\s]+$/ui", $value);
    }
    
    /**
     * Extend validation, add "alpha_numeric_spaces" rule, allow:
     * - alpha with accented characters (a-zA-ZÁÉÍÓÚáéíóú)
     * - numeric (0-9)
     * - spaces ( )
     *
     * @param   string  $attribute
     * @param   mixed   $string
     * @param   array   $parameters
     * 
     * @return  bool
     */ 
    public function validateAlphaNumericSpaces($attribute, $value, $parameters = null)
    {
        return (bool) preg_match("/^[\p{L}\s0-9]+$/ui", $value);
    }
    
    /**
     * Extend validation, add "alpha_dots" rule, allow:
     * - alpha without accented characters (a-zA-Z)
     * - dots (.)
     * 
     * @param   string  $attribute
     * @param   mixed   $string
     * @param   array   $parameters
     * 
     * @return  bool
     */ 
    public function validateAlphaDots($attribute, $value, $parameters)
    {
        return (bool) preg_match("/^[\p{L}.]+$/i", $value);
    }
    
    /**
     * Extend validation, add "text" rule, allow:
     * - alpha with accented characters (a-zA-ZÁÉÍÓÚáéíóú)
     * - numbers (0-9)
     * - spaces ( )
     * - dots (.)
     * - dashes (_-)
     * - arroba (@)
     * 
     * @param   string  $attribute
     * @param   mixed   $string
     * @param   array   $parameters
     * 
     * @return  bool
     */ 
    public function ValidateText($attribute, $value, $parameters)
    {
        return (bool) preg_match("/^[\p{L}.\s-@0-9]+$/ui", $value);
    }
}