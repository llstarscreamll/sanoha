<?php

namespace Page\WorkOrders;

use \common\BaseTest;
use \FunctionalTester;

class UpdateInternalReportPage
{
    // include url of current page
    public static $URL = '/workOrder/1/internalAccompanist/1/editReport';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    
    /**
     * El link de acceso a la edición del reporte del Trabajador 1 al cual el
     * actual usuario si tiene acceso
     */
    public static $linkAccess = [
        'txt'       =>  'Editar Reporte de Acompañante Interno',
        'selector'  =>  '#internal-accompanists .panel-default:first-child .panel-title .pull-right a.btn-warning'
    ];
    
    /**
     * El link de acceso a la edición del reporte del Trabajador 2 al cual el
     * actual usuario NO tiene acceso
     */
    public static $linkThatCantAccess = [
        'txt'       =>  'Editar Reporte de Acompañante Interno',
        'selector'  =>  '#internal-accompanists .panel-default:nth-child(2) .panel-title .pull-right a.btn-warning'
    ];
    
    public static $title = [
        'txt'       =>  'Editar Reporte de Acompañante Interno',
        'selector'  =>  'h1'
    ];
    
    public static $form = 'form#update-internal-report';
    
    public static $reportTextarea = [
        'selector'   =>  'textarea[name=work_order_report].form-control'
        ];
        
    public static $formButton = [
        'txt'       =>  'Actualizar',
        'selector'  =>  '.btn.btn-warning'
    ];
    
    public static $workReportFormData = [
        'work_order_report'   =>  'actualización de prueba al reporte del acompañante interno'
    ];
    
    public static $msgSuccess = [
        'txt'       =>  'Se ha guardado el reporte del acompañante interno.',
        'selector'  =>  '.alert.alert-success'
    ];
    
    public static $workReportViewLocation = '#internal-accompanists .panel-body';
    
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
     * @return UpdateInternalReportPage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }
}
