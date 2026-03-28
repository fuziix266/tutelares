<?php
namespace Noticia\Model\Factory;

use Noticia\Model\NoticiaTable;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class NoticiaTableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): NoticiaTable
    {
        $config = $container->get('config');
        $db     = $config['db'];
        $dsn    = "mysql:host={$db['host']};dbname={$db['database']};charset={$db['charset']}";
        $pdo    = new \PDO($dsn, $db['username'], $db['password'], [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        ]);

        return new NoticiaTable($pdo);
    }
}
