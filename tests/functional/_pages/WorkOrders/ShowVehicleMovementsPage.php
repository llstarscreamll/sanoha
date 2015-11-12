<?php
namespace Page\WorkOrders;

use \FunctionalTester;

class ShowVehicleMovementsPage
{
    // include url of current page
    public static $URL = '';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $accessTab = [
        'txt'       =>  'Vehículo',
        'selector'  =>  'a[href="#vehicle"]'
    ];

    /**
     * Mensaje de notificación de que no ha registrado salida
     */
    public static $noExitRegisteredMsg = [
        'txt'       =>  'No se ha registrado salida del vehículo',
        'selector'  =>  'div .alert-danger'
    ];

    /**
     * Mensaje de notificación de que no ha registrado entrada
     */
    public static $noEntryRegisteredMsg = [
        'txt'       =>  'No se ha registrado entrada del vehículo',
        'selector'  =>  'div .alert-danger'
    ];

    /**
     * EL lugar donde tabulados se muestran los datos de la salida
     */
    public static $exitDataPlace = '#exit tr td';

    /**
     * El label del tipo de movimiento del vehículo, rojo para salida
     */
    public static $exitDataLabel =  [
        'txt'       =>  'Salida',
        'selector'  =>  '.alert-danger'
    ];

    /**
     * Quien registró la salida y entrada, que en el test Page\WorkOrders\VehicleConditionPage
     * está definido como el usuario actual que realiza el test, el admin Travis Orbin
     */
    public static $registeredBy = 'Oribin Travis';

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
     * @return ShowVehicleMovementsPage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }
}