<?php

namespace sanoha\Providers;

use Illuminate\Support\ServiceProvider;

class CustomValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /**
         * Extend validation, add "alpha_spaces" rule, allow:
         * - alpha with accented characters (a-zA-ZÁÉÍÓÚáéíóú)
         * - spaces ( )
         */ 
        $this->app['validator']->extend('alpha_spaces', function ($attribute, $value, $parameters) {
            return (bool) preg_match("/^[\p{L}\s]+$/ui", $value);
        });
        
        /**
         * Extend validation, add "alpha_numeric_spaces" rule, allow:
         * - alpha with accented characters (a-zA-ZÁÉÍÓÚáéíóú)
         * - numbers (0-9)
         * - spaces ( )
         */ 
        $this->app['validator']->extend('alpha_numeric_spaces', function ($attribute, $value, $parameters) {
            return (bool) preg_match("/^[\p{L}\s0-9]+$/ui", $value);
        });
        
        /**
         * Extend validation, add "alpha_dots" rule, allow:
         * - alpha without accented characters (a-zA-Z)
         * - dots (.)
         */ 
        $this->app['validator']->extend('alpha_dots', function ($attribute, $value, $parameters) {
            return (bool) preg_match("/^[\p{L}.]+$/i", $value);
        });
        
        /**
         * Extend validation, add "text" rule, allow:
         * - alpha with accented characters (a-zA-ZÁÉÍÓÚáéíóú)
         * - numbers (0-9)
         * - spaces ( )
         * - dots (.)
         * - dashes (_-)
         * - arroba (@)
         */ 
        $this->app['validator']->extend('text', function ($attribute, $value, $parameters) {
            return (bool) preg_match("/^[\p{L}.\s-@0-9]+$/ui", $value);
        });
        
        
    }

    public function register()
    {
        //
    }
}