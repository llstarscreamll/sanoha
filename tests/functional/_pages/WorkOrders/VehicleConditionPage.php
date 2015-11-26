<?php
namespace Page\WorkOrders;

use Page\WorkOrders\WorkOrdersPage as BasePage;
use \FunctionalTester;

class VehicleConditionPage
{
    // include url of current page
    public static $URL = '';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * El botón de acceso para registrar la salida
     */
    public static $exitLink = [
        'txt'               =>  'Registrar Salida',
        'selector'          =>  'a.btn-danger',
        'selector.disabled' =>  'a[href="#"].btn-danger.disabled',
    ];

    /**
     * El botón de acceso para registrar la entrada
     */
    public static $entryLink = [
        'txt'               =>  'Registrar Entrada',
        'selector'          =>  'a.btn-success',
        'selector.disabled' =>  'a[href="#"].btn-success.disabled',
    ];

    /**
     * Los elementos del formulario para registrar la salida del vehículo
     */
    public static $formFields = [
        // kilometraje
        0 =>    [
            'input',
            'attr'    =>  ['name' => 'mileage'],
        ],
        // los campos del nivel de combustible
        1 =>    [
            'input',
            'attr'    =>  ['name' => 'fuel_level', 'type' => 'radio', 'value' => '1/4'],
        ],
        2 =>    [
            'input',
            'attr'    =>  ['name' => 'fuel_level', 'type' => 'radio', 'value' => '1/2'],
        ],
        3 =>    [
            'input',
            'attr'    =>  ['name' => 'fuel_level', 'type' => 'radio', 'value' => '3/4'],
        ],
        4 =>    [
            'input',
            'attr'    =>  ['name' => 'fuel_level', 'type' => 'radio', 'value' => '1'],
        ],
        // aseo interior
        5 =>    [
            'input',
            'attr'    =>  ['name' => 'internal_cleanliness', 'type' => 'radio', 'value' => 'Bueno'],
        ],
        6 =>    [
            'input',
            'attr'    =>  ['name' => 'internal_cleanliness', 'type' => 'radio', 'value' => 'Regular'],
        ],
        7 =>    [
            'input',
            'attr'    =>  ['name' => 'internal_cleanliness', 'type' => 'radio', 'value' => 'Malo'],
        ],
        // aseo exterior
        8 =>    [
            'input',
            'attr'    =>  ['name' => 'external_cleanliness', 'type' => 'radio', 'value' => 'Bueno'],
        ],
        9 =>    [
            'input',
            'attr'    =>  ['name' => 'external_cleanliness', 'type' => 'radio', 'value' => 'Regular'],
        ],
        10 =>    [
            'input',
            'attr'    =>  ['name' => 'external_cleanliness', 'type' => 'radio', 'value' => 'Malo'],
        ],
        // estado de pintura
        11 =>    [
            'input',
            'attr'    =>  ['name' => 'paint_condition', 'type' => 'radio', 'value' => 'Bueno'],
        ],
        12 =>    [
            'input',
            'attr'    =>  ['name' => 'paint_condition', 'type' => 'radio', 'value' => 'Regular'],
        ],
        13 =>    [
            'input',
            'attr'    =>  ['name' => 'paint_condition', 'type' => 'radio', 'value' => 'Malo'],
        ],
        // estado de la carroceria
        14 =>    [
            'input',
            'attr'    =>  ['name' => 'bodywork_condition', 'type' => 'radio', 'value' => 'Bueno'],
        ],
        15 =>    [
            'input',
            'attr'    =>  ['name' => 'bodywork_condition', 'type' => 'radio', 'value' => 'Regular'],
        ],
        16 =>    [
            'input',
            'attr'    =>  ['name' => 'bodywork_condition', 'type' => 'radio', 'value' => 'Malo'],
        ],
        // llanta delantera derecha
        17 =>    [
            'input',
            'attr'    =>  ['name' => 'right_front_wheel_condition', 'type' => 'radio', 'value' => 'Bueno'],
        ],
        18 =>    [
            'input',
            'attr'    =>  ['name' => 'right_front_wheel_condition', 'type' => 'radio', 'value' => 'Regular'],
        ],
        19 =>    [
            'input',
            'attr'    =>  ['name' => 'right_front_wheel_condition', 'type' => 'radio', 'value' => 'Malo'],
        ],
        // llanta delantera izquierda
        20 =>    [
            'input',
            'attr'    =>  ['name' => 'left_front_wheel_condition', 'type' => 'radio', 'value' => 'Bueno'],
        ],
        21 =>    [
            'input',
            'attr'    =>  ['name' => 'left_front_wheel_condition', 'type' => 'radio', 'value' => 'Regular'],
        ],
        22 =>    [
            'input',
            'attr'    =>  ['name' => 'left_front_wheel_condition', 'type' => 'radio', 'value' => 'Malo'],
        ],
        // llanta trasera derecha
        23 =>    [
            'input',
            'attr'    =>  ['name' => 'rear_right_wheel_condition', 'type' => 'radio', 'value' => 'Bueno'],
        ],
        24 =>    [
            'input',
            'attr'    =>  ['name' => 'rear_right_wheel_condition', 'type' => 'radio', 'value' => 'Regular'],
        ],
        25 =>    [
            'input',
            'attr'    =>  ['name' => 'rear_right_wheel_condition', 'type' => 'radio', 'value' => 'Malo'],
        ],
        // llanta trasera izquierda
        26 =>    [
            'input',
            'attr'    =>  ['name' => 'rear_left_wheel_condition', 'type' => 'radio', 'value' => 'Bueno'],
        ],
        27 =>    [
            'input',
            'attr'    =>  ['name' => 'rear_left_wheel_condition', 'type' => 'radio', 'value' => 'Regular'],
        ],
        28 =>    [
            'input',
            'attr'    =>  ['name' => 'rear_left_wheel_condition', 'type' => 'radio', 'value' => 'Malo'],
        ],
        29 =>    [
            'textarea',
            'attr'    =>  ['name' => 'comment']
        ]
    ];

    /**
     * El botón que envía el formulario cuando es registro de entrada
     */
    public static $submitEntryBtn = [
        'txt'       =>  'Registrar Entrada',
        'selector'  =>  'button.btn.btn-success'
    ];

    /**
     * El botón que envía el formulario cuando es registro de entrada
     */
    public static $submitExitBtn = [
        'txt'       =>  'Registrar Salida',
        'selector'  =>  'button.btn.btn-danger'
    ];

    /**
     * Los datos de prueba para registrar la SALIDA del vehículo
     */
    public static $vehicleExitData = [
        'mileage'                       =>  '100',
        'fuel_level'                    =>  '3/4',
        'internal_cleanliness'          =>  'Bueno',
        'external_cleanliness'          =>  'Bueno',
        'paint_condition'               =>  'Bueno',
        'bodywork_condition'            =>  'Bueno',
        'right_front_wheel_condition'   =>  'Bueno',
        'left_front_wheel_condition'    =>  'Bueno',
        'rear_right_wheel_condition'    =>  'Bueno',
        'rear_left_wheel_condition'     =>  'Bueno',
        'comment'                       =>  'Comentario de prueba....'
    ];

    /**
     * Los datos de prueba para registrar la ENTRADA del vehículo
     */
    public static $vehicleEntryData = [
        'mileage'                       =>  '150',
        'fuel_level'                    =>  '1/2',
        'internal_cleanliness'          =>  'Malo',
        'external_cleanliness'          =>  'Regular',
        'paint_condition'               =>  'Regular',
        'bodywork_condition'            =>  'Regular',
        'right_front_wheel_condition'   =>  'Regular',
        'left_front_wheel_condition'    =>  'Regular',
        'rear_right_wheel_condition'    =>  'Regular',
        'rear_left_wheel_condition'     =>  'Regular',
        'comment'                       =>  'El carro llego estrellado por detras'
    ];

    /**
     * Mensaje de exito al registrar salida
     */
    public static $exitSuccessMsg = [
        'txt'       =>  'Salida registrada correctamente.',
        'selector'  =>  '.alert-success'
    ];

    /**
     * Mensaje de exito al registrar entrada
     */
    public static $entrySuccessMsg = [
        'txt'       =>  'Entrada registrada correctamente.',
        'selector'  =>  '.alert-success'
    ];

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var FunctionalTester;
     */
    protected $functionalTester;

    public function __construct(FunctionalTester $I)
    {
        $this->functionalTester = $I;
    }

    /**
     * @return VehicleConditionPage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }

    /**
     * Registra la salida de un vehículo relacionado a una orden de trabajo
     */
    public static function registerVehicleExit(FunctionalTester $I)
    {
        // estoy en el index donde están las ordenes de trabajo listadas
        // y ya debe haber sido creada una orden de trabajo...
        $I->amOnPage(BasePage::$URL);

        // doy clic al link para registrar la salida del vehiculo relacionado
        // a esa orden de trabajo
        $I->click(self::$exitLink['txt'], self::$exitLink['selector']);

        // estoy en la página del registro de salida del vehículo
        $I->seeCurrentUrlEquals(BasePage::route('/1/vehicleMovement/exit'));

        // veo información básica
        // quien autorizó
        $I->see(BasePage::$createFormData['authorized_by']);
        // el responsable principal
        $I->see(BasePage::$createFormData['vehicle_responsable_name']);
        // las placas del vehículo
        $I->see(BasePage::$createFormData['vehicle_plate']);
        // el destino
        $I->see(BasePage::$createFormData['destination']);
        // veo los elementos del formulario
        foreach (self::$formFields as $key => $field) {
            $I->seeElement($field[0], $field['attr']);
        }
        // veo el botón para registrar SALIDA
        $I->see(self::$submitExitBtn['txt'], self::$submitExitBtn['selector']);

        // envío el formulario
        $I->submitForm('form', self::$vehicleExitData);

        // soy redirigido al index de módulo
        $I->seeCurrentUrlEquals(BasePage::$URL);
        // veo mensaje de éxito en la operación
        $I->see(self::$exitSuccessMsg['txt'], self::$exitSuccessMsg['selector']);
        // el link para registrar salida debe estar deshabilitado
        $I->see(self::$exitLink['txt'], self::$exitLink['selector.disabled']);
    }

    /**
     * Registra la entrada de un vehiculo relacionado a una orden de trabajo
     */
    public static function registerVehicleEntry($I)
    {
        // estoy en el index donde están las ordenes de trabajo listadas,
        // y ya debe haber sido creada una orden de trabajo...
        $I->amOnPage(BasePage::$URL);

        // el botón de registrar entrada debe aparecer
        $I->see(self::$entryLink['txt'], self::$entryLink['selector']);

        // ahora registro la entrada del vehículo
        $I->click(self::$entryLink['txt'], self::$entryLink['selector']);

        // estoy en la página del registro de ENTRADA del vehículo
        $I->seeCurrentUrlEquals(BasePage::route('/1/vehicleMovement/entry'));
        // el botón de registrar entrada es diferente al de salida
        $I->see(self::$submitEntryBtn['txt'], self::$submitEntryBtn['selector']);
        $I->submitForm('form', self::$vehicleEntryData);

        // soy redirigido al index del módulo
        $I->seeCurrentUrlEquals(BasePage::$URL);
        // veo mensaje de éxito en la operación
        $I->see(self::$entrySuccessMsg['txt'], self::$entrySuccessMsg['selector']);
        // veo el link de registrar entrada deshabilitado
        $I->see(self::$entryLink['txt'], self::$entryLink['selector.disabled']);
    }
}
