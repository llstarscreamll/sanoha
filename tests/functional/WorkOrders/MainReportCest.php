<?php

namespace WorkOrder;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage;

class MainReportCest
{
    public function _before(FunctionalTester $I)
    {
        new WorkOrdersPage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de crear el reporte principal de la orden de trabajo
     */ 
    public function createMainReport(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('crear reporte principal de orden de trabajo');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        $I->amOnPage(WorkOrdersPage::route('/1'));
        $I->see(WorkOrdersPage::$showTitle, WorkOrdersPage::$showTitleTag);
        
        $I->click(WorkOrdersPage::$mainReportLink['txt'], WorkOrdersPage::$mainReportLink['selector']);
        $I->seeCurrentUrlEquals(WorkOrdersPage::route('/1/mainReport'));
        
        $I->see(WorkOrdersPage::$mainReportTitle['txt'], WorkOrdersPage::$mainReportTitle['selector']);
        $I->seeElement(WorkOrdersPage::$mainReportTextarea['selector']);
        
        $I->submitForm(WorkOrdersPage::$mainReportForm, WorkOrdersPage::$workReportFormData, WorkOrdersPage::$mainReportFormButton['txt']);
        
        $I->seeCurrentUrlEquals(WorkOrdersPage::route('/1'));
        
        $I->see(WorkOrdersPage::$workReportFormData['work_order_report'], WorkOrdersPage::$workOrderReportBodyLocation);
        $I->see(WorkOrdersPage::getWorkOrderReportFooter(), WorkOrdersPage::$workOrderReportFooterLocation);
    }
}