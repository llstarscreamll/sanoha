<?php

namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use \Page\WorkOrders\TrashMainReportPage as Page;

class TrashReportCest
{
    public function _before(FunctionalTester $I)
    {
        new BasePage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la uncionalidad de mover a la papelera un reporte de orden de trabajo
     */ 
    public function trashReport(FunctionalTester $I)
    {
        $I->am(BasePage::$mainActor);
        $I->wantTo('editar reporte principal de orden de trabajo');
        
        // creo la orden de trabajo de prueba
        BasePage::createWorkOrder($I);
        
        // creo el reporte principal
        BasePage::createMainReport($I);
        
        $I->amOnPage(BasePage::route('/1'));
        
        $I->see(Page::$accessBtn['txt'], Page::$accessBtn['selector']);
        
        /**
         * -----------
         * Aquí hay que hacer el envío del formulario con la función _loadPage()
         * que sólo está disponible en los helpers de codeception en la versión 2.1
         * ----------
         */ 
        $I->amOnRoute(Page::$route, ['main_report_id' => 1]);
        
        $I->seeCurrentUrlEquals(BasePage::route('/1'));
        $I->see(Page::$msgSuccess['txt'], Page::$msgSuccess['selector']);
        
        $I->dontSee(BasePage::$workReportFormData['work_order_report'], BasePage::$workOrderReportBodyLocation);
    }
}