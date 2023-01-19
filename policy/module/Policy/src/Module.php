<?php
namespace Policy;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PolicyTable::class => function($container) {
                    $tableGateway = $container->get(Model\PolicyTableGateway::class);
                    return new Model\PolicyTable($tableGateway);
                },
                Model\PolicyTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Policy());
                    return new TableGateway('policy', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
    
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\PolicyController::class => function($container) {
                    return new Controller\PolicyController(
                        $container->get(Model\PolicyTable::class)
                    );
                },
            ],
        ];
    }
}