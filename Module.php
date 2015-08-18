<?php

namespace IyvZF2PommProfiler;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getTarget()->getServiceManager();
        $pommProfiler = $serviceManager->get('pommProfiler');
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return require __DIR__ . '/config/module.php';
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
}
