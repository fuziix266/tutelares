<?php
namespace Admin\Controller\Factory;

use Admin\Controller\NoticiaAdminController;
use Admin\Model\NoticiaTable;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class NoticiaAdminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): NoticiaAdminController
    {
        return new NoticiaAdminController($container->get(\Noticia\Model\NoticiaTable::class));
    }
}
