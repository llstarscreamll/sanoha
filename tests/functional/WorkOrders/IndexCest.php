<?php

namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage;

class IndexCest
{
    public function _before(FunctionalTester $I)
    {
        new WorkOrdersPage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo los datos mostrados en el index del módulo
     */ 
    public function checkTableData(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('ver lista de ordendes de trabajo creadas');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        $I->amOnPage(WorkOrdersPage::$URL);
        
        $I->see(WorkOrdersPage::$indexPageTitle);
        
        $I->see('', WorkOrdersPage::$indexPageTable);
        
        // veo los datos recientemente creados en la tabla
        $I->see('1', 'tbody tr:first-child td a');
        $I->see(WorkOrdersPage::$createFormData['authorized_by'], 'tbody tr:first-child td');
        $I->see(WorkOrdersPage::$createFormData['vehicle_responsable_name'], 'tbody tr:first-child td');
        $I->see(WorkOrdersPage::$createFormData['vehicle_plate'], 'tbody tr:first-child td');
        $I->see(WorkOrdersPage::$createFormData['destination'], 'tbody tr:first-child td');
        $I->see(\Carbon\Carbon::now()->toDateString(), 'tbody tr:first-child td');
    }
}