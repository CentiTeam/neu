<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        		
        	'anmelden' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/anmelden[/:action]',
        						'defaults' => [
        								'controller' => Controller\AnmeldenController::class,
        								'action'     => 'anmelden',
        			],
        		],
        	],
        		
        	'overview' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/overview[/:action]',
        					'defaults' => [
        							'controller' => Controller\OverviewController::class,
        							'action'     => 'overview',
        					],
        			],
        	],
        		
        		'anlegen' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/GruppeAnlegen',
        						'defaults' => [
        								'controller' => Controller\GruppeController::class,
        								'action'     => 'anlegen',
        						],
        				],
        		],
        		
        		'help' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/help[/:action]',
        						'defaults' => [
        								'controller' => Controller\HelpController::class,
        								'action'     => 'help',
        						],
        				],
        		],
        		
        	'login' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/login[/:action]',
        					'defaults' => [
        							'controller' => Controller\LoginController::class,
        							'action'     => 'login',
        					],
        			],
        	],
        	
        
        	'user' => [
        		'type'    => Segment::class,
        		'options' => [
        			'route' => '/user[/:action[/:id]]',
        				'constraints' => [
        				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        				'id'     => '[0-9]+',
        						],
        				'defaults' => [
        				    'controller' => Controller\UserController::class,
        					 'action'     => 'index',
        						],
        				],
        		],
        		
        		        		
        ],
    		
    ],
		
 
		
		
		
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        	Controller\OverviewController::class => InvokableFactory::class,
        	Controller\UserController::class => InvokableFactory::class,
        	Controller\GruppeController::class => InvokableFactory::class,
        	Controller\LoginController::class => InvokableFactory::class,
           	Controller\HelpController::class => InvokableFactory::class,
        	Controller\HalloController::class => InvokableFactory::class,
        	Controller\AnmeldenController::class => InvokableFactory::class,
        		
        		

        		
        	
        		
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
        	'application/overview/overview' => __DIR__ . '/../view/application/overview/overview.phtml',
        	'application/hilfe/hilfe' => __DIR__ . '/../view/application/hilfe/hilfe.phtml',
        	'application/GruppeAnlegen/GruppeAnlegen' => __DIR__ . '/../view/application/GruppeAnlegen/GruppeAnlegen.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
