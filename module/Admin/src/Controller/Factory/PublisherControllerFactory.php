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
        $config = $container->get('config');
        $db     = $config['db'];
        $dsn    = "mysql:host={$db['host']};dbname={$db['database']};charset={$db['charset']}";
        $pdo    = new \PDO($dsn, $db['username'], $db['password'], [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
        return new PublisherController(new NoticiaTable($pdo));
    }
}
