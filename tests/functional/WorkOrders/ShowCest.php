<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrdersPage;

class ShowCest
{
    public function _before(FunctionalTester $I)
    {
        new WorkOrdersPage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de ver los detalles de una orden de trabajo (sólo lectura)
     */ 
    public function showWorkOrder(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('ver los detalles de una orden de trabajo');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        $I->amOnPage(WorkOrdersPage::$URL);
        
        // veo el registro en la tabla
        $I->see('1', 'tbody tr:first-child td a');
        $I->see(WorkOrdersPage::$createFormData['authorized_by'], 'tbody tr:first-child td');
        
        $I->click('1', WorkOrdersPage::$workOrderDetailsLink);
        
        $I->amOnPage(WorkOrdersPage::route('/1'));
        
        // compruebo que la información sea la correcta
        WorkOrdersPage::checkDataOnShowRoute(WorkOrdersPage::$createFormData, $I);
    }
}