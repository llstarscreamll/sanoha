<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use \Page\WorkOrders\UpdateMainReportPage as Page;

class UpdateMainReportCest
{
    public function _before(FunctionalTester $I)
    {
        new BasePage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de editar un reporte principal ya creado 
     */ 
    public function updateReport(FunctionalTester $I)
    {
        $I->am(BasePage::$mainActor);
        $I->wantTo('editar reporte principal de orden de trabajo');
        
        // creo la orden de trabajo de prueba
        BasePage::createWorkOrder($I);
        
        // creo el reporte principal
        BasePage::createMainReport($I);
        
        $I->amOnPage(BasePage::route('/1'));
        // veo el botón para acceder al formulario de actualización
        $I->seeElement(Page::$linkAccess['selector']);
        $I->click(Page::$linkAccess['txt'], Page::$linkAccess['selector']);
        $I->seeCurrentUrlEquals(Page::$URL);
        
        // y ahora si lo actualizo
        $I->see(Page::$title['txt'], Page::$title['selector']);
        $I->see(BasePage::$workReportFormData['work_order_report'], BasePage::$mainReportTextarea['selector']);
        $I->seeElement(Page::$updateForm);
        $I->seeElement(Page::$submitButton['selector']);
        
        $I->submitForm(Page::$updateForm, [
            BasePage::$mainReportTextarea['name'] => Page::$formData['txt']
        ], Page::$submitButton['txt']);
        
        $I->seeCurrentUrlEquals(BasePage::route('/1'));
        
        // mensaje de éxito en la operación
        $I->see(Page::$msgSuccess['txt'], Page::$msgSuccess['selector']);
        //dd(\sanoha\Models\WorkOrderReport::all()->toArray());
        $I->amOnPage(BasePage::route('/1'));
        $I->see(Page::$formData['txt'], BasePage::$workOrderReportBodyLocation);
    }
}