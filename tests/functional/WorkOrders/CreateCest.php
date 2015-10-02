<?php

namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrdersPage;

class CreateCest
{
    public function _before(FunctionalTester $I)
    {
        new WorkOrdersPage($I);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de crear una orden de trabajo
     */ 
    public function createWorkOrder(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('crear una orden de trabajo para ciertos empleados');
        
        $I->amOnPage(WorkOrdersPage::route('/create'));
        
        $I->see(WorkOrdersPage::$createPageTitle, WorkOrdersPage::$createPageTitleTag);
        
        $I->seeElement(WorkOrdersPage::$createForm);
        // campo informativo, la info de quien reporta  se toma de la sesión del actual usuario
        $I->seeElement(WorkOrdersPage::$authorizedByField);
        $I->seeElement(WorkOrdersPage::$vehicleResponsableField);
        $I->seeElement(WorkOrdersPage::$vehicleIdField);
        $I->seeElement(WorkOrdersPage::$destinationField);
        $I->seeElement(WorkOrdersPage::$internalAccompanistsField);
        $I->seeElement(WorkOrdersPage::$externalAccompanistsField);
        $I->seeElement(WorkOrdersPage::$workDescriptionField);

        $I->submitForm(WorkOrdersPage::$createForm, WorkOrdersPage::$createFormData, WorkOrdersPage::$createFormButtonCaption);
        
        $I->seeCurrentUrlEquals(WorkOrdersPage::$URL);
        
        $I->see(WorkOrdersPage::$msgWorkOrderCreation['success'], WorkOrdersPage::$msgClass['success']);
        $I->see(WorkOrdersPage::$msgWorkOrderCreation['internal_accompanists_success'], WorkOrdersPage::$msgClass['success']);
        $I->see(WorkOrdersPage::$msgWorkOrderCreation['exteral_accompanists_success'], WorkOrdersPage::$msgClass['success']);
    }
}