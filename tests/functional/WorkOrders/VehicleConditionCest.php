<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use Page\WorkOrders\VehicleConditionPage as Page;

class VehicleConditionCest
{
    public function _before(FunctionalTester $I)
    {
        new BasePage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba el registro de la condiciones en que entra el vehiculo
     */
    public function registerVehicleExitAndEntry(FunctionalTester $I)
    {
        $I->am('portero');
        $I->wantTo('registrar la salida de una orden de trabajo');

        // creo la orden de trabajo
        BasePage::createWorkOrder($I);

        // registro la salida del vehículo
        Page::registerVehicleExit($I);

        // registro la entrada del vehículo
        Page::registerVehicleEntry($I);
    }
}