<?php
namespace Radio;
use Laminas\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'radio' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/radio',
                    'defaults' => ['controller' => Controller\RadioController::class, 'action' => 'index'],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\RadioController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
];
