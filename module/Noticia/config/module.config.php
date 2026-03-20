<?php
namespace Noticia;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'noticia' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/noticia',
                    'defaults' => ['controller' => Controller\NoticiaController::class, 'action' => 'index'],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\NoticiaController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
];
