<?php   namespace sanoha\Providers;

use \Carbon\Carbon;

class CustomValidator extends \Illuminate\Validation\Validator
{
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
    public function validateAlphaNumericSpaces($attribute, $value, $parameters)
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