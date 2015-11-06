<?php

namespace Page\WorkOrders;

use \common\BaseTest;
use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use \Page\WorkOrders\EraseInternalReportPage as ErasePage;
use \Page\WorkOrders\UpdateInternalReportPage as UpdatePage;

class InternalAccompanistReportPage
{
    // include url of current page
    public static $URL = '/workOrder/1/internalAccompanist/1/report';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
     
    /**
     * El link de acceso a la creación del reporte del trabajador 1 al cual el
     * actual usuario si tiene acceso
     */
    public static $linkToAccess = [
        'txt'       =>  'Crear Reporte de Actividades Realizadas',
        'selector'  =>  '#internal-accompanists .panel-default:first-child .panel-title .pull-right a.btn-primary'
        ];
    
    /**
     * La sección donde está el link de acceso a la creació del reporte del
     * trabajador 2, del cual no tiene acceso el actual usuario
     */ 
    public static $linkThatCantAccess = [
        'txt'       =>  'Crear Reporte de Actividades Realizadas',
        'selector'  =>  '#internal-accompanists .panel-default:nth-child(2) .panel-title .pull-right a.btn-primary'
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
        'txt'       =>  'Se ha guardado el reporte del acompañante interno.',
        'selector'  =>  '.alert-success'
        ];
    
    // en donde se mostrará lo que reportó el acompañante interno
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
    
    /**
     * Crea el reporte de un acompañante interno
     */
    public static function createInternalReport(FunctionalTester $I)
    {
        $I->amOnPage(BasePage::route('/1'));
        
        // sólo veo el link para crear el reporte del trabajador que tengo 
        // asignado (el de código 1, es decir Trabajador 1)
        $I->see(static::$linkToAccess['txt'], static::$linkToAccess['selector']);
        
        // no puedo ver el otro link porqu no soy "dueño de la información" o no
        // tengo asignado al otro empleado
        $I->dontSee(static::$linkThatCantAccess['txt'], static::$linkThatCantAccess['selector']);
        
        // no veo los links para borrar ni borrar porque el reporte no se ha creado
        $I->dontSee(ErasePage::$linkAccess['txt'], ErasePage::$linkAccess['selector']);
        $I->dontSee(UpdatePage::$linkAccess['txt'], UpdatePage::$linkAccess['selector']);
        
        // no veo los links para borrar ni borrar porque el reporte del otro trabajador no se ha creado
        $I->dontSee(ErasePage::$linkThatCantAccess['txt'], ErasePage::$linkThatCantAccess['selector']);
        $I->dontSee(UpdatePage::$linkThatCantAccess['txt'], UpdatePage::$linkThatCantAccess['selector']);
        
        $I->click(static::$linkToAccess['txt'], static::$linkToAccess['selector']);
        
        $I->seeCurrentUrlEquals(static::$URL);
        $I->see(static::$mainReportTitle['txt'], static::$mainReportTitle['selector']);
        $I->seeElement(static::$mainReportForm);
        $I->seeElement(static::$mainReportTextarea['selector']);
        $I->submitForm(static::$mainReportForm, static::$workReportFormData, static::$mainReportFormButton['txt']);
        
        $I->seeCurrentUrlEquals(BasePage::route('/1'));
        $I->see(static::$msgSuccess['txt'], static::$msgSuccess['selector']);
    }
}