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
        
        // creo el reporte principal
        WorkOrdersPage::createMainReport($I);
    }
}