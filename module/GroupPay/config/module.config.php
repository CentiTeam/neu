<?php


namespace GroupPay;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
		'controllers' => [
				'factories' => [
						Controller\GroupPayController::class => InvokableFactory::class,
				],
		],
		 'router' => [
        'routes' => [
            'groupPay' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/groupPay[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\GroupPayController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
		
		'view_manager' => [
				'template_map' => [
						'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
						'groupPay/groupPay/index' => __DIR__ . '/../view/groupPay/groupPay/index.phtml',
				],
				
				'template_path_stack' => [
						'groupPay' => __DIR__ . '/../view',
				],
		],
];