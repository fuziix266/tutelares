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
                'may_terminate' => true,
                'child_routes'  => [
                    'ver' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/ver/:id',
                            'constraints' => ['id' => '[0-9]+'],
                            'defaults' => ['action' => 'ver'],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\NoticiaTable::class => Model\Factory\NoticiaTableFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\NoticiaController::class => Controller\Factory\NoticiaControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
];
