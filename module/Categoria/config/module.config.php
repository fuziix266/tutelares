<?php
namespace Categoria;
use Laminas\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'categoria' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/categoria',
                    'defaults' => ['controller' => Controller\CategoriaController::class, 'action' => 'index'],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\CategoriaController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
];
