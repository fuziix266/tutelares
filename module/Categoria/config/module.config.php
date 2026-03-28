<?php
namespace Categoria;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'categoria' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/categoria[/:id]',
                    'defaults' => ['controller' => Controller\CategoriaController::class, 'action' => 'index'],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\CategoriaController::class => Controller\Factory\CategoriaControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
];
