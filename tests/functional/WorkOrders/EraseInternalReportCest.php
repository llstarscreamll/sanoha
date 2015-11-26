<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use \Page\WorkOrders\EraseInternalReportPage as Page;
use \Page\WorkOrders\InternalAccompanistReportPage as InternalReportPage;

class EraseInternalReportCest
{
    public function _before(FunctionalTester $I)
    {
        new BasePage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba la funcionalidad de borrar el reporte de un acompañante interno de
     * la orden de trabajo
     */
    public function eraseInternalReport(FunctionalTester $I)
    {
        $I->am(BasePage::$mainActor);
        $I->wantTo('borrar reporte de acompanante interno de orden de trabajo');
        
        // creo la orden de trabajo de prueba
        BasePage::createWorkOrder($I);
        
        // creo el reporte del acompañante interno
        InternalReportPage::createInternalReport($I);
        
        $I->amOnPage(BasePage::route('/1'));
        
        // no veo el botón de crear reporte pero si el de borrar reporte
        $I->dontSee(InternalReportPage::$linkToAccess['txt'], InternalReportPage::$linkToAccess['selector']);
        
        // tampoco puedo ver el link para editar la orden del Trabajador 2, no lo tengo asignado
        $I->dontSee(Page::$linkThatCantAccess['txt'], Page::$linkThatCantAccess['selector']);
        
        // veo el link pra borrar el reporte del trabajador que tengo asignado
        $I->see(Page::$linkAccess['txt'], Page::$linkAccess['selector']);
        $I->click(Page::$linkAccess['txt'], Page::$linkAccess['selector']);
        
        $I->seeCurrentUrlEquals(BasePage::route('/1'));
        $I->see(Page::$msgSuccess['txt'], Page::$msgSuccess['selector']);
        
        // ahora veo el botón para crear el reporte pero no el de borrar reporte
        $I->see(InternalReportPage::$linkToAccess['txt'], InternalReportPage::$linkToAccess['selector']);
        $I->dontSee(Page::$linkAccess['txt'], Page::$linkAccess['selector']);
    }
}
