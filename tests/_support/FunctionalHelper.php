<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class FunctionalHelper extends \Codeception\Module
{
    /**
     * Cargo páginas con dtos dinámicos, para por ejemplo simular el envío de formularios
     * por el metodo, url y datos que quiera. La función _loadPage() puede recibir los
     * siguientes parámetros:
     * 
     * param $method
     * param $uri
     * param array $parameters
     * param array $files
     * param array $server
     * param null $content
     * 
     * @param array $params
     */ 
    /*public function loadDynamicPage($params)
    {
        $this->getModule('Laravel5')->_loadPage($params['method'], $params['url'], $params['data']);
    }*/
}
