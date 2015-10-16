<?php

namespace Employees;

use \FunctionalTester;
use \common\BaseTest as BaseTest;

class StatusCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTest;
        $base_test->permissionsCommons->createActivityReportsModulePermissions();
        $base_test->employees();

        $I->amLoggedAs($base_test->admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Pruebo la funcionalidad de cambiar el estado de los empleados, de inactivo
     * a retirado por ejemplo
     */ 
    public function toggleStatus(FunctionalTester $I)
    {
        $I->am('admin de recurso humano');
        $I->wantTo('ediat el estado de un empleado');
        
        $I->amOnPage('/employee');
        
        $I->see('B1 Trabajador 1', 'tbody tr:nth-child(12) td'); // el último trabajador
        $I->see('Activado', 'tbody tr:nth-child(12) td span.text-success');
        
        // el botón que envía el formulario mediante javascript
        $I->see('Desactivar Empleado(s)', 'button.btn-default');
        $I->see('Activar Empleado(s)', 'button.btn-default');
        
        $params = [
            'method'    =>  'PUT',
            'url'       =>  '/employee/status/disabled',
            'data'      =>  [
                'id'        =>  1,
                '_token'    =>  csrf_token(),
                '_method'   =>  'PUT'
            ]
        ];
        
        $I->loadDynamicPage($params);
        
        $I->seeCurrentUrlEquals('/employee');
        $I->see('El empleado ha sido desactivado correctamente.', '.alert-success');
        
        // veo que en la tabla cambia el dato
        $I->see('Desactivado', 'tbody tr:first-child td span.text-danger');
        
        // ahora que esta desactivado el empleado, este no debe ser cargado en
        // las listas de los select de por ejemplo los reporttes de novedades o
        // reportes de actividades mineras, queda invisible en dichos módulos...
        
        // hago clic en el proyecto que quiero trabajar
        $I->click('Proyecto Beteitiva', 'a');
        // doy clic al botón de registrar una actividad minera
        $I->click('Registrar Labor Minera', 'a');
        // veo que estoy en la url indicada
        $I->seeCurrentUrlEquals('/activityReport/create');
        $I->see('Reportar Labor Minera', 'fieldset legend');
        $I->dontSee('B1 Trabajador 1', 'select optgroup option');
        $I->see('B2 Trabajador 2', 'select optgroup option');

        // realizo, el cambio de nuevo, es al mismo método del controlador, el
        // cambio debe ser detectado automáticamente mediante el último parámetro
        // de la url
        $params['url'] = '/employee/status/disabled';
        $params['data']['id'] = [6,5,4];
        $I->loadDynamicPage($params);
        
        $I->seeCurrentUrlEquals('/employee');
        $I->see('Los empleados han sido desactivados correctamente.', '.alert-success');
        
        // veo que en la tabla cambia el dato
        $I->see('Desactivado', 'tbody tr:nth-child(1) td span.text-danger');
        $I->see('Desactivado', 'tbody tr:nth-child(2) td span.text-danger');
        $I->see('Desactivado', 'tbody tr:nth-child(3) td span.text-danger');
    }
}