<?php
namespace NoveltiesReports;

use \FunctionalTester;
use \common\BaseTest;

class AccessLinksCest
{
    public function _before(FunctionalTester $I)
    {
        $base_test = new BaseTest;
        $base_test->noveltyReports();

        $I->amLoggedAs($base_test->admin_user);
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * 
     */
    public function seeAccessLinks(FunctionalTester $I)
    {
        $I->am('supervisor de minas');
        $I->wantTo('ver los links de acceso a los reportes de novedades de mis proyectos');
        
        // estoy en el home
        $I->amOnPage('/home');
        
        // veo que están listados los links de acceso al módulo de reporte de novedades
        // de los proyectos que tengo asignados
        $I->see('Proyecto Beteitiva', '#noveltyReports ul li');
        $I->see('Proyecto Sanoha', '#noveltyReports ul li');
        
        // no veo los que no tengo asignados
        $I->dontSee('Proyecto Cazadero', '#noveltyReports ul li');
        $I->dontSee('Proyecto Curital', '#noveltyReports ul li');
        $I->dontSee('Proyecto Escalera', '#noveltyReports ul li');
        $I->dontSee('Proyecto Pinos', '#noveltyReports ul li');
        
        // doy clic al proyecto del que quiero ver las novedades reportadas
        $I->click('Proyecto Beteitiva', '#noveltyReports ul li a');
        
        // veo que estoy en el index del módulo de reporte de novedades
        $I->seeCurrentUrlEquals('/noveltyReport');
        
        // veo el título de la página
        $I->see('Reportes de Novedad', 'h1');
        
        // veo el nombre del proyecto que seleccioné
        $I->see('Proyecto Beteitiva', 'th');
    }
}
