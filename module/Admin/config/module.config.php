<?php
namespace Admin;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            // ── AUTH ────────────────────────────────────────────────────
            'admin-login' => [
                'type' => Literal::class ,
                'options' => [
                    'route' => '/admin/login',
                    'defaults' => ['controller' => Controller\AuthController::class , 'action' => 'login'],
                ],
            ],
            'admin-logout' => [
                'type' => Literal::class ,
                'options' => [
                    'route' => '/admin/logout',
                    'defaults' => ['controller' => Controller\AuthController::class , 'action' => 'logout'],
                ],
            ],

            // ── PUBLISHER ────────────────────────────────────────────────
            'admin-publisher' => [
                'type' => Segment::class ,
                'options' => [
                    'route' => '/admin/publisher[/:action[/:id]]',
                    'constraints' => ['action' => '[a-zA-Z][a-zA-Z0-9_-]*', 'id' => '[0-9]+'],
                    'defaults' => [
                        'controller' => Controller\PublisherController::class ,
                        'action' => 'index',
                    ],
                ],
            ],

            // ── RADIO ────────────────────────────────────────────────────
            'admin-radio' => [
                'type' => Literal::class ,
                'options' => [
                    'route' => '/admin/radio-panel',
                    'defaults' => ['controller' => Controller\RadioAdminController::class , 'action' => 'index'],
                ],
            ],

            // ── SUPER USUARIO ────────────────────────────────────────────
            'admin-super' => [
                'type' => Literal::class ,
                'options' => [
                    'route' => '/admin/super',
                    'defaults' => ['controller' => Controller\SuperController::class , 'action' => 'index'],
                ],
            ],

            // ── NOTICIA (Panel Global o Legacy) ─────────────────────────
            'admin-noticia' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/noticia[/:action[/:id]]',
                    'constraints' => ['action' => '[a-zA-Z][a-zA-Z0-9_-]*', 'id' => '[0-9]+'],
                    'defaults' => [
                        'controller' => Controller\NoticiaAdminController::class,
                        'action' => 'index',
                    ],
                ],
            ],

            // ── Ruta legacy /admin (redirige al login) ───────────────────
            'admin' => [
                'type' => Literal::class ,
                'options' => [
                    'route' => '/admin',
                    'defaults' => ['controller' => Controller\AuthController::class , 'action' => 'login'],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class ,
            Controller\PublisherController::class => Controller\Factory\PublisherControllerFactory::class ,
            Controller\RadioAdminController::class => Controller\Factory\RadioAdminControllerFactory::class ,
            Controller\SuperController::class => Controller\Factory\SuperControllerFactory::class ,
            // Mantener el NoticiaAdminController por compatibilidad
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class ,
            Controller\NoticiaAdminController::class => Controller\Factory\NoticiaAdminControllerFactory::class ,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
        'template_map' => [
            'layout/admin' => __DIR__ . '/../view/layout/admin.phtml',
            'layout/admin-login' => __DIR__ . '/../view/layout/admin-login.phtml',
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\SocialMediaService::class => Service\Factory\SocialMediaServiceFactory::class,
        ],
    ],

    'social_media' => [
        'facebook' => [
            'page_id'      => getenv('FB_PAGE_ID') ?: '',
            'access_token' => getenv('FB_ACCESS_TOKEN') ?: '',
        ],
        'instagram' => [
            'user_id'      => getenv('IG_USER_ID') ?: '',
            'access_token' => getenv('IG_ACCESS_TOKEN') ?: '',
        ],
    ],

    'db' => [
        'driver' => 'Pdo_Mysql',
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'database' => getenv('DB_NAME') ?: 'tutelares',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
];
