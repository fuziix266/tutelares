<?php
namespace Admin\Controller\Factory;

use Admin\Controller\SuperController;
use Admin\Model\NoticiaTable;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class SuperControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): SuperController
    {
        return new SuperController(
            $container->get(\Noticia\Model\NoticiaTable::class),
            $container->get(\Admin\Model\UsuarioTable::class),
            $container->get(\Admin\Model\ConfigTable::class)
        );
    }
}
