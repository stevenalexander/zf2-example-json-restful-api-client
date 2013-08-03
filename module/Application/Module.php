<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Http\Client as HttpClient;
use Application\HttpRestJson\Client as HttpRestJsonClient;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\HttpRestJson\Client' => function($serviceManager) {
                    $httpClient = $serviceManager->get('HttpClient');
                    $httpRestJsonClient = new HttpRestJsonClient($httpClient);
                    return $httpRestJsonClient;
                },
                'HttpClient' => function($serviceManager) {
                    $httpClient = new HttpClient();
                    # use curl adapter as standard has problems with ssl
                    $httpClient->setAdapter('Zend\Http\Client\Adapter\Curl');
                    return $httpClient;
                },
            ),
        );
    }
}
