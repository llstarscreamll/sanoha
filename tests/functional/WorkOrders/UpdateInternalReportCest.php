<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use \Page\WorkOrders\UpdateInternalReportPage as Page;
use \Page\WorkOrders\InternalAccompanistReportPage as InternalReportPage;

class UpdateInternalReportCest
{
    public function _before(FunctionalTester $I)
    {
        new BasePage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba la funcionalidad de editar un reporte de actividades realizadas
     * en una orden de trabajo por un acompañante interno
     */
    public function updateInternalReport(FunctionalTester $I)
    {
        $I->am(BasePage::$mainActor);
        $I->wantTo('editar reporte de acompanante interno de orden de trabajo');
        
        // creo la orden de trabajo de prueba
        BasePage::createWorkOrder($I);
        
        // creo el reporte del acompañante interno
        InternalReportPage::createInternalReport($I);
        
        $I->amOnPage(BasePage::route('/1'));
        
        // no veo el link para crear el reporte porqu éso ya está hecho
        $I->dontSee(InternalReportPage::$linkToAccess['txt'], InternalReportPage::$linkToAccess['selector']);
        
        // tampoco puedo ver el link para editar la orden del Trabajador 2
        $I->dontSee(Page::$linkThatCantAccess['txt'], Page::$linkThatCantAccess['selector']);
        
        // doy clic al botón para crear el reporte del trabajador que si tengo a cargo
        $I->click(Page::$linkAccess['txt'], Page::$linkAccess['selector']);
        
        $I->seeCurrentUrlEquals(Page::$URL);
        $I->see(Page::$title['txt'], Page::$title['selector']);
        $I->seeElement(Page::$form);
        $I->see(InternalReportPage::$workReportFormData['work_order_report'], Page::$reportTextarea['selector']);
        
        $I->submitForm(Page::$form, Page::$workReportFormData, Page::$formButton['txt']);
        $I->see(Page::$msgSuccess['txt'], Page::$msgSuccess['selector']);
        $I->see(Page::$workReportFormData['work_order_report'], Page::$workReportViewLocation);
    }
}
