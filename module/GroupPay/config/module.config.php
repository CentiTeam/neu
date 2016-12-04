<?php


namespace GroupPay;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
		'controllers' => [
				'factories' => [
						Controller\IndexController::class => InvokableFactory::class,
				],
		],
		 'router' => [
        'routes' => [
            'grouppay' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/grouppay[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
		

		'template_path_stack' => [
				'grouppay' => __DIR__ . '/../view',
		],
		'view_manager' => [
				'display_not_found_reason' => true,
				'display_exceptions'       => true,
				'doctype'                  => 'HTML5',
				'not_found_template'       => 'error/404',
				'exception_template'       => 'error/index',
				'template_map' => [
						'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
						'grouppay/index/index' => __DIR__ . '/../view/grouppay/index/index.phtml',
						'error/404'               => __DIR__ . '/../view/error/404.phtml',
						'error/index'             => __DIR__ . '/../view/error/index.phtml',
				],
		
		],
];