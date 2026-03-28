<?php
namespace Admin\Service\Factory;

use Admin\Service\SocialMediaService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SocialMediaServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): SocialMediaService
    {
        $config = $container->get('config');
        $socialConfig = $config['social_media'] ?? [];
        return new SocialMediaService($socialConfig);
    }
}
