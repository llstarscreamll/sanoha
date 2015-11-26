<?php

namespace WorkOrders;

use \FunctionalTester;
use \Page\WorkOrders\WorkOrdersPage;

class EditCest
{
    public function _before(FunctionalTester $I)
    {
        new WorkOrdersPage($I);

        // actualizo al segundo trabajador para que sea autorizado
        // para el manejo de vehículos
        \sanoha\Models\Employee::find(2)->update(['authorized_to_drive_vehicles' => true]);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de editar la info de una orden de trabajo
     */
    public function editWorkOrder(FunctionalTester $I)
    {
        $I->am(WorkOrdersPage::$mainActor);
        $I->wantTo('editar informacion de una orden de trabajo');
        
        // creo la orden de trabajo de prueba
        WorkOrdersPage::createWorkOrder($I);
        
        $I->amOnPage(WorkOrdersPage::$URL);
        
         // veo el registro en la tabla
        $I->see('1', 'tbody tr:first-child td a');
        $I->see(WorkOrdersPage::$createFormData['authorized_by'], 'tbody tr:first-child td');
        
        $I->click('1', WorkOrdersPage::$workOrderDetailsLink);
        
        $I->amOnPage(WorkOrdersPage::route('/1'));
        
        $I->see(WorkOrdersPage::$showTitle, WorkOrdersPage::$showTitleTag);
        
        $I->click(WorkOrdersPage::$editFormAccessBtn['txt'], WorkOrdersPage::$editFormAccessBtn['class']);
        
        $I->seeCurrentUrlEquals(WorkOrdersPage::route('/1/edit'));
        $I->see(WorkOrdersPage::$editPageTitle, WorkOrdersPage::$editPageTitleTag);
        
        $I->seeInFormFields(WorkOrdersPage::$editFormName, [
            'authorized_by'             =>  WorkOrdersPage::$createFormData['authorized_by'],
            'vehicle_responsable'       =>  WorkOrdersPage::$createFormData['vehicle_responsable'],
            'vehicle_id'                =>  WorkOrdersPage::$createFormData['vehicle_id'],
            'destination'               =>  WorkOrdersPage::$createFormData['destination'],
            'work_description'          =>  WorkOrdersPage::$createFormData['work_description'],
            'internal_accompanists[]'   =>  WorkOrdersPage::$createFormData['internal_accompanists'],
            'external_accompanists[]'   =>  WorkOrdersPage::$createFormData['external_accompanists']
        ]);
        
        $I->submitForm(WorkOrdersPage::$editFormName, WorkOrdersPage::$editFormData, WorkOrdersPage::$editFormButtonCaption);
        
        $I->seeCurrentUrlEquals(WorkOrdersPage::route('/1'));
        $I->see(WorkOrdersPage::$editFormSuccessMsg['txt'], WorkOrdersPage::$editFormSuccessMsg['class']);
        
        // compruebo los datos actualizados en la vista de sólo lectura (show)
        WorkOrdersPage::checkDataOnShowRoute(WorkOrdersPage::$editFormData, $I);
        
        // compruebo lo que no debe estar...
        $I->dontSee(WorkOrdersPage::$createFormData['external_accompanists'][0], WorkOrdersPage::$externalsAccompanistsLocation);
        $I->dontSee(WorkOrdersPage::$createFormData['external_accompanists'][1], WorkOrdersPage::$externalsAccompanistsLocation);
    }
}
