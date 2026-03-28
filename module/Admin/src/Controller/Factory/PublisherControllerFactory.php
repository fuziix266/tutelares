<?php
namespace Admin\Controller\Factory;

use Admin\Controller\PublisherController;
use Admin\Model\NoticiaTable;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class PublisherControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): PublisherController
    {
        return new PublisherController(
            $container->get(\Noticia\Model\NoticiaTable::class),
            $container->get(\Admin\Service\SocialMediaService::class)
        );
    }
}
