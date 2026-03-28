<?php
namespace Noticia\Controller\Factory;

use Noticia\Controller\NoticiaController;
use Noticia\Model\NoticiaTable;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class NoticiaControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): NoticiaController
    {
        return new NoticiaController(
            $container->get(NoticiaTable::class)
        );
    }
}
