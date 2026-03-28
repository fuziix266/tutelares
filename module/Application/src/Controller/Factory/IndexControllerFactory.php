<?php
namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Noticia\Model\NoticiaTable;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): IndexController
    {
        return new IndexController(
            $container->get(NoticiaTable::class)
        );
    }
}
