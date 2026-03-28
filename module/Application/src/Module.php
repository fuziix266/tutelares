<?php
declare(strict_types=1);

namespace Application;

use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;

class Module
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $sm  = $app->getServiceManager();

        $em->attach(MvcEvent::EVENT_DISPATCH, function($e) use ($sm) {
            $route = $e->getRouteMatch();
            if (!$route) return;

            $routeName = $route->getMatchedRouteName();

            // Allow admin routes and login
            if (strpos($routeName, 'admin') === 0) return;

            // Check portal status
            $configTable = $sm->get(\Admin\Model\ConfigTable::class);
            $status = $configTable->get('portal_status', 'activo');

            if ($status === 'standby') {
                if (session_status() === PHP_SESSION_NONE) session_start();
                if (!empty($_SESSION['admin_role'])) return;

                $vm = new ViewModel();
                $vm->setTemplate('application/index/maintenance');
                // Pass custom maintenance message
                $msg = $configTable->get('portal_message', 'El portal está en mantenimiento. Por favor, vuelva pronto.');
                $vm->setVariable('message', $msg);

                $renderer = $sm->get('ViewRenderer');
                $content = $renderer->render($vm);

                $response = $e->getResponse();
                $response->setStatusCode(503);
                $response->setContent($content);
                $e->stopPropagation();
                return $response;
            }
        }, 100);
    }
}
