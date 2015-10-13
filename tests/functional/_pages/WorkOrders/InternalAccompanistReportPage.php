<?php

namespace Page\WorkOrders;

use \FunctionalTester;
use \common\BaseTest;

class InternalAccompanistReportPage
{
    // include url of current page
    public static $URL = '/workOrder/1/internalAccompanist/1/report';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $linkToAccess = [
        'txt'       =>  'Crear Reporte de Actividades Realizadas',
        'selector'  =>  '.panel-title .pull-right a.btn-primary'
        ];
    
     public static $mainReportTitle = [
        'txt'       =>  'Reportar Actividades de la Orden de Trabajo',
        'selector'  =>  'h1'
        ];
        
    public static $mainReportForm = 'form#report-work-order-activity';
        
    public static $mainReportTextarea = [
        'selector'   =>  'textarea[name=work_order_report].form-control'
        ];
    
    public static $mainReportFormButton = [
        'txt'       =>  'reportar',
        'selector'  =>  'button.btn-primary'
        ];
        
    public static $workReportFormData = ['work_order_report'   =>  'reporte de prueba para el acompañante interno'];
    
    public static $msgSuccess = [
        'txt'       =>  'El reporte ha sido creado correctamente.',
        'selector'  =>  '.alert-success'
        ];
    
    // en donde se mostrará lo que reportó el acmopañante interno
    public static $workReportViewLocation = '.panel-body';
    public static $workReportedByData = [
        'reported_by'   =>  'Orbin Travis',
        'selector'      =>  '.panel-footer.text-right strong'
        ];
    public static $workReportedAtData = [
        'selector'      =>  '.panel-footer.text-right strong'
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
     * @return InternalAccompanistReportPage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }
}