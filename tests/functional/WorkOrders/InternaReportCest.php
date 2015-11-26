<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use \Page\WorkOrders\InternalAccompanistReportPage as Page;

class InternaReportCest
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
        
        // creo la orden de trabajo
        BasePage::createWorkOrder($I);
        
        // creo el reporte del acompañante interno
        Page::createInternalReport($I);
        
        // veo lo que reportó el empleado en la página de los detalles de a orden de trabajo
        $I->see(Page::$workReportFormData['work_order_report'], Page::$workReportViewLocation);
        // veo quien reportó
        $I->see(Page::$workReportedByData['reported_by'], Page::$workReportedByData['selector']);
        // veo cuando se reportó
        $I->see(date('Y-m-d'), Page::$workReportedAtData['selector']);
    }
}
