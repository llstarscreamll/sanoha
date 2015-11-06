<?php

namespace Page\WorkOrders;

use \FunctionalTester;
use \common\BaseTest;

class UpdateMainReportPage
{
    // include url of current page
    public static $URL = '/workOrder/1/mainReport/1/edit';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $linkAccess = [
        'txt'       =>  'Actualizar Reporte Principal',
        'selector'  =>  '.panel-footer a.btn-warning'
        ];
    
    public static $title = [
        'txt'       =>  'Editar Reporte de Actividades de Orden de Trabajo',
        'selector'  =>  'h1'
        ];
    
    public static $updateForm = 'form#edit-main-work-order-report';
    
    public static $submitButton = [
        'txt'       =>  'Actualizar',
        'selector'  =>  'button.btn.btn-warning'
        ];
    
    public static $formData = [
        'txt'       =>  'actualización de reporte',
        ];
    
    public static $msgSuccess = [
        'txt'       =>  'Reporte principal actualizado correctamente.',
        'selector'  =>  '.alert.alert-success'
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
     * @return UpdateMainReportPage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }
}