<?php
namespace Admin\Controller\Factory;

use Admin\Controller\IndexController;
use Admin\Model\NoticiaTable;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): IndexController
    {
        return new IndexController($container->get(\Noticia\Model\NoticiaTable::class));
    }
}
