<?php

namespace Page\WorkOrders;

use \FunctionalTester;

class TrashMainReportPage
{
    // include url of current page
    public static $URL = '/workOrder/mainReport/1/destroy';
    
    public static $route = 'workOrder.mainReportDestroy';
    
    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    
    public static $accessBtn = [
        'txt'       =>      'Mover a Papelera el Reporte Principal',
        'selector'  =>      '.btn.btn-danger.btn-sm'
        ];
        
    public static $msgSuccess = [
        'txt'       =>      'El reporte principal ha sido movido a la papelera correctamente.',
        'selector'  =>      '.alert.alert-success'
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
     * @return TrashMainReportPage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }
}