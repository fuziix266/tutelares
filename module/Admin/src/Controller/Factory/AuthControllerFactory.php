<?php
namespace Admin\Controller\Factory;

use Admin\Controller\AuthController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthController
    {
        return new AuthController();
    }
}
