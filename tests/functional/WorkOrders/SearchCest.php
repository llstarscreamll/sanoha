<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage;

class SearchCest
{
    public function _before(FunctionalTester $I)
    {
        new WorkOrdersPage($I);
        // creo algunas ordenes de trabajo mas
        WorkOrdersPage::createExtraWorkOrdersData();
        // modifico los nombres de los trabajadores para probar mejor las búsquedas de estos
        \sanoha\Models\Employee::find(1)->update(['name' => 'Andrés', 'lastname' => 'Alarcon']);
        \sanoha\Models\Employee::find(2)->update(['name' => 'Bryan', 'lastname' => 'Barrera']);
        \sanoha\Models\Employee::find(3)->update(['name' => 'Camilo', 'lastname' => 'Castillo']);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Búsqueda por fecha de creación y destino de la orden de trabajo
     */
    public function byWorkOrderDateAndDestinity(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('buscar una orden de trabajo por fecha de creacion y destino');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        // estoy en el index del módulo
        $I->amOnPage(WorkOrdersPage::$URL);
        // veo el titulo de la página
        $I->see(WorkOrdersPage::$indexPageTitle);
        // veo la tabla donde están los datos
        $I->see('', WorkOrdersPage::$indexPageTable);
        // veo todos los registros
        $I->see(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // Trabajador 2
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // Trabajador 3
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // Trabajador 1

        // hago la búsqueda
        $I->submitForm('form[name=search]', [
            'find'  =>  'Escalera',
            'from'  => '2015-11-01',
            'to'    => '2015-11-30',
        ], 'Buscar');

        // la página es recargada y veo sólo el registro que coincide con mi criterio de búsqueda
        $I->dontSee(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // Trabajador 2
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // Trabajador 3 con destino Escalera regitrada en el 2015-11-09 15:30:00
        $I->dontSee(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // Trabajador 1
    }
    
    /**
     * Búsqueda por fecha de creación de la orden de trabajo
     */
    public function byWorkOrderDates(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('buscar una orden de trabajo por fecha de creacion');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        // estoy en el index del módulo
        $I->amOnPage(WorkOrdersPage::$URL);
        // veo el titulo de la página
        $I->see(WorkOrdersPage::$indexPageTitle);
        // veo la tabla donde están los datos
        $I->see('', WorkOrdersPage::$indexPageTable);
        // veo todos los registros
        $I->see(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // Trabajador 2
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // Trabajador 3
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // Trabajador 1

        // hago la búsqueda
        $I->submitForm('form[name=search]', [
            'from'  => '2015-09-01',
            'to'    => '2015-09-30',
        ], 'Buscar');

        // la página es recargada y veo sólo el registro que coincide con mi criterio de búsqueda
        $I->see(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // Trabajador 2
        $I->dontSee(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // Trabajador 3
        $I->dontSee(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // Trabajador 1
    }

    /**
     * Prueba habilidad de buscar una orden de trabajo por su destino
     */
    public function byDestinity(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('buscar una orden de trabajo por destino');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        // estoy en el index del módulo
        $I->amOnPage(WorkOrdersPage::$URL);
        // veo el titulo de la página
        $I->see(WorkOrdersPage::$indexPageTitle);
        // veo la tabla donde están los datos
        $I->see('', WorkOrdersPage::$indexPageTable);
        // veo todos los registros
        $I->see(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td');
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td');
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td');

        // hago la búsqueda
        $I->submitForm('form[name=search]', [
            'find'  => 'beteitiva'
        ], 'Buscar');

        // la página es recargada y veo sólo el registro que coincide con mi criterio de búsqueda
        $I->dontSee(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td');
        $I->dontSee(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td');
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td');
    }

    /**
     * Búsqueda por número de placa de vehículo
     */
    public function byVehiclePlate(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('buscar una orden de trabajo por el numero de placa');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        // estoy en el index del módulo
        $I->amOnPage(WorkOrdersPage::$URL);
        // veo el titulo de la página
        $I->see(WorkOrdersPage::$indexPageTitle);
        // veo la tabla donde están los datos
        $I->see('', WorkOrdersPage::$indexPageTable);
        // veo todos los registros
        $I->see(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // placa BBB222
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // placa AAA111
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // placa AAA111

        // hago la búsqueda
        $I->submitForm('form[name=search]', [
            'find'  => 'aaa111'
        ], 'Buscar');

        // la página es recargada y veo sólo el registro que coincide con mi criterio de búsqueda
        $I->dontSee(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // placa BBB222
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // placa AAA111
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // placa AAA111
    }

    /**
     * Búsqueda por nombre del responsable
     */
    public function byResponsibleName(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('buscar una orden de trabajo por nombre de responsable');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        // estoy en el index del módulo
        $I->amOnPage(WorkOrdersPage::$URL);
        // veo el titulo de la página
        $I->see(WorkOrdersPage::$indexPageTitle);
        // veo la tabla donde están los datos
        $I->see('', WorkOrdersPage::$indexPageTable);
        // veo todos los registros
        $I->see(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // Trabajador 2
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // Trabajador 3
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // Trabajador 1

        // hago la búsqueda
        $I->submitForm('form[name=search]', [
            'find'  => 'Andrés'
        ], 'Buscar');

        // la página es recargada y veo sólo el registro que coincide con mi criterio de búsqueda
        $I->dontSee(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // Trabajador 2
        $I->dontSee(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // Trabajador 3
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // Trabajador 1
    }

    /**
     * Búsqueda por apellido del responsable
     */
    public function byResponsibleLastname(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('buscar una orden de trabajo por apellido de responsable');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        // estoy en el index del módulo
        $I->amOnPage(WorkOrdersPage::$URL);
        // veo el titulo de la página
        $I->see(WorkOrdersPage::$indexPageTitle);
        // veo la tabla donde están los datos
        $I->see('', WorkOrdersPage::$indexPageTable);
        // veo todos los registros
        $I->see(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // Trabajador 2
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // Trabajador 3
        $I->see(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // Trabajador 1

        // hago la búsqueda
        $I->submitForm('form[name=search]', [
            'find'  => 'Castillo'
        ], 'Buscar');

        // la página es recargada y veo sólo el registro que coincide con mi criterio de búsqueda
        $I->dontSee(WorkOrdersPage::$extraData[0]['destination'], 'table tbody tr td'); // Trabajador 2
        $I->see(WorkOrdersPage::$extraData[1]['destination'], 'table tbody tr td'); // Trabajador 3
        $I->dontSee(WorkOrdersPage::$createFormData['destination'], 'table tbody tr td'); // Trabajador 1
    }
}