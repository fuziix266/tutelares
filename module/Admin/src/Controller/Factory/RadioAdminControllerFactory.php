<?php
namespace Admin\Controller\Factory;

use Admin\Controller\RadioAdminController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class RadioAdminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RadioAdminController
    {
        return new RadioAdminController();
    }
}
