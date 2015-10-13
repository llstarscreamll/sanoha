<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\InternalAccompanistReportPage as Page;
use \Page\WorkOrders\WorkOrdersPage as BasePage;

class InternalAccompanistReportCest
{
    public function _before(FunctionalTester $I)
    {
        new BasePage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de reportar actividades por parte de los
     * acompañantes internos de la orden de trabajo
     */ 
    public function internalReport(FunctionalTester $I)
    {
        $I->am('acompañante interno de la orden de trabajo');
        $I->wantTo('reportar las actividades realizadas en la orden');
        
        BasePage::createWorkOrder($I);
        
        $I->amOnPage(BasePage::route('/1'));
        $I->click(Page::$linkToAccess['txt'], Page::$linkToAccess['selector']);
        
        $I->seeCurrentUrlEquals(Page::$URL);
        $I->see(Page::$mainReportTitle['txt'], Page::$mainReportTitle['selector']);
        $I->seeElement(Page::$mainReportForm);
        $I->seeElement(Page::$mainReportTextarea['selector']);
        $I->submitForm(Page::$mainReportForm, Page::$workReportFormData, Page::$mainReportFormButton['txt']);
        
        $I->seeCurrentUrlEquals(BasePage::route('/1'));
        $I->see(Page::$msgSuccess['txt'], Page::$msgSuccess['selector']);
        
        // veo lo que reportó el empleado en la página de los detalles de a orden de trabajo
        $I->see(Page::$workReportFormData['work_order_report'], Page::$workReportViewLocation);
        // veo quien reportó
        $I->see(Page::$workReportedByData['reported_by'], Page::$workReportedByData['selector']);
        // veo cuando se reportó
        $I->see(date('Y-m-d'), Page::$workReportedAtData['selector']);
    }
}