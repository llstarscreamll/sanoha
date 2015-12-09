<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    "accepted"         => ":attribute debe ser aceptado.",
    "active_url"       => ":attribute no es una URL válida.",
    "after"            => ":attribute debe ser una fecha posterior a :date.",
    "alpha"            => ":attribute solo debe contener letras.",
    "alpha_dash"       => ":attribute solo debe contener letras, números y guiones.",
    "alpha_num"        => ":attribute solo debe contener letras y números.",
    "array"            => ":attribute debe ser un conjunto.",
    "before"           => ":attribute debe ser una fecha anterior a :date.",
    "between"          => [
        "numeric" => ":attribute tiene que estar entre :min - :max.",
        "file"    => ":attribute debe pesar entre :min - :max kilobytes.",
        "string"  => ":attribute tiene que tener entre :min - :max caracteres.",
        "array"   => ":attribute tiene que tener entre :min - :max ítems.",
    ],
    "boolean"          => "El campo :attribute debe tener un valor verdadero o falso.",
    "confirmed"        => "La confirmación de :attribute no coincide.",
    "date"             => ":attribute no es una fecha válida.",
    "date_format"      => ":attribute no corresponde al formato :format.",
    "different"        => ":attribute y :other deben ser diferentes.",
    "digits"           => ":attribute debe tener :digits dígitos.",
    "digits_between"   => ":attribute debe tener entre :min y :max dígitos.",
    "email"            => ":attribute no es un correo válido",
    "exists"           => ":attribute es inválido.",
    "filled"           => "El campo :attribute es obligatorio.",
    "image"            => ":attribute debe ser una imagen.",
    "in"               => ":attribute es inválido.",
    "integer"          => ":attribute debe ser un número entero.",
    "ip"               => ":attribute debe ser una dirección IP válida.",
    "max"              => [
        "numeric" => ":attribute no debe ser mayor a :max.",
        "file"    => ":attribute no debe ser mayor que :max kilobytes.",
        "string"  => ":attribute no debe ser mayor que :max caracteres.",
        "array"   => ":attribute no debe tener más de :max elementos.",
    ],
    "mimes"            => ":attribute debe ser un archivo con formato: :values.",
    "min"              => [
        "numeric" => "El tamaño de :attribute debe ser de al menos :min.",
        "file"    => "El tamaño de :attribute debe ser de al menos :min kilobytes.",
        "string"  => ":attribute debe contener al menos :min caracteres.",
        "array"   => ":attribute debe tener al menos :min elementos.",
    ],
    "not_in"           => ":attribute es inválido.",
    "numeric"          => ":attribute debe ser numérico.",
    "regex"            => "El formato de :attribute es inválido.",
    "required"         => "El campo :attribute es obligatorio.",
    "required_if"      => "El campo :attribute es obligatorio cuando :other es :value.",
    "required_with"    => "El campo :attribute es obligatorio cuando :values está presente.",
    "required_with_all" => "El campo :attribute es obligatorio cuando :values está presente.",
    "required_without" => "El campo :attribute es obligatorio cuando :values no está presente.",
    "required_without_all" => "El campo :attribute es obligatorio cuando ninguno de :values estén presentes.",
    "same"             => ":attribute y :other deben coincidir.",
    "size"             => [
        "numeric" => "El tamaño de :attribute debe ser :size.",
        "file"    => "El tamaño de :attribute debe ser :size kilobytes.",
        "string"  => ":attribute debe contener :size caracteres.",
        "array"   => ":attribute debe contener :size elementos.",
    ],
    "string"           => "El campo :attribute debe ser de tipo texto.",
    "timezone"         => "El :attribute debe ser una zona válida.",
    "unique"           => "El valor del campo :attribute ya ha sido registrado.",
    "url"              => "El formato :attribute es inválido.",
    
    // custom rules messages
    "alpha_numeric_spaces"  =>  ":attribute tiene un formato inválido, sólo se permiten letras, espacios y/o números.",
    "alpha_spaces"          =>  ":attribute tiene un formato inválido, sólo se permiten letras y/o espacios.",
    "alpha_dots"            =>  ":attribute tiene un formato inválido, sólo se permiten letras y/o puntos.",
    "text"                  =>  ":attribute tiene un formato inválido, sólo se permiten letras, números, espacios, puntos, comas, slash (/), guiones y/o arroba.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        /**
         * Atributos de usuario
         */
        'role_id'                       =>  'El tipo de usuario',
        'sub_cost_center_id'            =>  'El subcentro de costo',
        'employee_id'                   =>  'El empleado',
        /**
         * Atributos de ordenes de trabajo
         */ 
        'internal_accompanists_name'    =>  'El acompañante interno',
        'external_accompanists'         =>  'El acompañante externo',
        'vehicle_responsable'           =>  'El responsable del vehículo',
        'vehicle_id'                    =>  'El vehículo',
        'destination'                   =>  'El destino',
        'work_description'              =>  'La descrición',
        // el atributo para los reportes
        'work_order_report'             =>  'El reporte',
        // campos de registro de salida/entrada de vehículos
        'mileage'                       =>  'El kilometraje',
        'fuel_level'                    =>  'El nivel de gasolina',
        'internal_cleanliness'          =>  'La limpieza interna',
        'external_cleanliness'          =>  'La limpieza externa',
        'paint_condition'               =>  'El estado de la pintura',
        'bodywork_condition'            =>  'El estado de la carrocería',
        'right_front_wheel_condition'   =>  'El estado de la llanta Delantera Derecha',
        'left_front_wheel_condition'    =>  'El estado de la llanta Delantera Izquierda',
        'rear_right_wheel_condition'    =>  'El estado de la llanta Trasera Derecha',
        'rear_left_wheel_condition'     =>  'El estado de la llanta Trasera Izquierda',
        'comment'                       =>  'El comentario'
    ],

];
