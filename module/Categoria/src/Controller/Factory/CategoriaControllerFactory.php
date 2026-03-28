<?php
namespace Categoria\Controller\Factory;
use Categoria\Controller\CategoriaController;
use Noticia\Model\NoticiaTable;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CategoriaControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CategoriaController(
            $container->get(NoticiaTable::class)
        );
    }
}
