<?php
namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage as BasePage;
use \Page\WorkOrders\TrashWorkOrderPage as Page;

class TrashWorkOrderCest
{
    public function _before(FunctionalTester $I)
    {
        new BasePage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
         $I->am(BasePage::$mainActor);
        $I->wantTo('editar reporte principal de orden de trabajo');
        
        // creo la orden de trabajo de prueba
        BasePage::createWorkOrder($I);
        
        // estoy en la página que muestra los detalles de la orden
        $I->amOnPage(Page::$URL);
        
        // veo el botón de borrar
        $I->see(Page::$btnTrash['txt'], Page::$btnTrash['selector']);
        
        // el botón de borrar abre una ventana modal de confirmación, cambia los
        // atributos del formulario y demas cosas y envía el formulario todo
        // atraves de javascript, por lo tanto par aprobarlo aquí debo usar la
        // función _loadPage() del modulo de laravel5 codeception disponible
        // desde la versión 2.1
        
        
    }
}