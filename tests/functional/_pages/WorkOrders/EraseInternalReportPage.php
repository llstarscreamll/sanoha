<?php

namespace Page\WorkOrders;

use \common\BaseTest;
use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;

class EraseInternalReportPage
{
    // include url of current page
    public static $URL = '';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    
    /**
     * El link de acceso a borrado del reporte del Trabajador 1 al cual el
     * actual usuario si tiene acceso
     */
    public static $linkAccess = [
        'txt'       =>  'Borrar Reporte de Acompañante Interno',
        'selector'  =>  '#internal-accompanists .panel-default:first-child .panel-title .pull-right form button.btn.btn-danger.btn-xs'
    ];
    
    /**
     * El link de acceso a borrado del reporte del Trabajador 2 al cual el
     * actual usuario NO tiene acceso
     */
    public static $linkThatCantAccess = [
        'txt'       =>  'Borrar Reporte de Acompañante Interno',
        'selector'  =>  '#internal-accompanists .panel-default:bth-child(2) .panel-title .pull-right form button.btn.btn-danger.btn-xs'
    ];
    
    public static $msgSuccess = [
        'txt'       =>  'El reporte del acompañante interno ha sido borrado correctamente.',
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
     * @return EraseInternalReportPage
     */
    public static function of(FunctionalTester $I)
    {
        return new static($I);
    }
}