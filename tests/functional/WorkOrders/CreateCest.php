<?php

namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage;

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
        // campo informativo, la info de quien reporta  se toma de la sesión del
        // actual usuario
        $I->seeElement(WorkOrdersPage::$authorizedByField);
        
        // en el campo de responsable de vehículo sólo debo ver a los trabajadores
        // que están autorizados para manejar vehículos
        foreach (WorkOrdersPage::$employeesAuthorizedToDriveVehicles as $key => $value) {
            $I->see($value, WorkOrdersPage::$vehicleResponsableField);
        }
        
        // no veo los empleados que no están autorizados para el manejo de vehículos
        foreach (WorkOrdersPage::$employeesUnauthorizedToDriveVehicles as $key => $value) {
            $I->dontSee($value, WorkOrdersPage::$vehicleResponsableField);
        }
        
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
    
    /**
     * Pruebo los mensajes de error en la creación de la orden de trabajo
     */
    public function formRequest(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('probar los errores al crear una orden de trabajo');
        
        $I->amOnPage(WorkOrdersPage::route('/create'));
        $I->see(WorkOrdersPage::$createPageTitle, WorkOrdersPage::$createPageTitleTag);
        
        foreach (WorkOrdersPage::$formRequestValidation as $key => $value) {
            
            $I->submitForm(WorkOrdersPage::$createForm, $value['data'], WorkOrdersPage::$createFormButtonCaption);
            $I->seeCurrentUrlEquals(WorkOrdersPage::route('/create'));
            
            foreach ($value['msg'] as $field => $msg) {
                $I->seeFormErrorMessage($field, $msg);
            }
            
        }
    }
}