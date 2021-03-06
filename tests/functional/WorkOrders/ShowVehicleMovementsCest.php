<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\VehicleConditionPage;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use \Page\WorkOrders\ShowVehicleMovementsPage as Page;

class ShowVehicleMovementsCest
{
    public function _before(FunctionalTester $I)
    {
        new BasePage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Prueba la funcionalidad de ver la información de las entradas
     * y salidas del vehículo relacionado a la orden de trabajo
     */
    public function showEmptyVehicleMovementsDetails(FunctionalTester $I)
    {
        $I->am('portero');
        $I->wantTo('ver que no tengo informacion de movimientos de vehiculo de orden de trabajo');

        // creo la orden de trabajo
        BasePage::createWorkOrder($I);

        // estoy en la página de detalles de la orden
        $I->amOnPage(BasePage::route('/1'));

        // veo la pestaña de accesso la info del vehículo
        $I->see(Page::$accessTab['txt'], Page::$accessTab['selector']);
        // veo mensaje de que no ha registrado salida y tampoco entrada
        $I->see(Page::$nothingRegisteredMsg['txt'], Page::$nothingRegisteredMsg['selector']);

        // registro la salida del vehículo
        VehicleConditionPage::registerVehicleExit($I);

        // vuelvo a la página de detalles de la orden
        $I->amOnPage(BasePage::route('/1'));

        // veo la etiqueta de que el tipo de movimiento esa salida
        $I->see(Page::$exitLabel['txt'], Page::$exitLabel['selector']);
        // veo los detalles del registro de la salida
        foreach (VehicleConditionPage::$vehicleExitData as $key => $value) {
            $I->see($value, Page::$exitDataPlace);
        }
        // veo quien registró la salida y cuando fue registrada, que es el usuario actual
        $I->see(Page::$registeredBy, Page::$exitDataPlace);
        $I->see(\Carbon\Carbon::now()->toDateString(), Page::$exitDataPlace);

        // veo mensaje de que no ha sido registrada la entrada
        $I->see(Page::$noEntryRegisteredMsg['txt'], Page::$noEntryRegisteredMsg['selector']);
        
        // registro la ENTRADA del vehículo
        VehicleConditionPage::registerVehicleEntry($I);

        // vuelvo a la página de detalles de la orden
        $I->amOnPage(BasePage::route('/1'));

        // veo la etiqueta de que el tipo de movimiento esa salida
        $I->see(Page::$entryLabel['txt'], Page::$entryLabel['selector']);
        // veo los detalles del registro de la salida
        foreach (VehicleConditionPage::$vehicleEntryData as $key => $value) {
            $I->see($value, Page::$entryDataPlace);
        }
        // veo quien registró la salida y cuando fue registrada, que es el usuario actual
        $I->see(Page::$registeredBy, Page::$entryDataPlace);
        $I->see(\Carbon\Carbon::now()->toDateString(), Page::$entryDataPlace);

        // no debo ver ningún mensaje de falta de registro de entrada o salida
        $I->dontSee(Page::$noEntryRegisteredMsg['txt'], Page::$noEntryRegisteredMsg['selector']);
        $I->dontSee(Page::$noExitRegisteredMsg['txt'], Page::$noExitRegisteredMsg['selector']);
    }
}
