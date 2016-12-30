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
        		
        		
        	'adminoverview' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/adminoverview[/:action]',
        					'defaults' => [
        							'controller' => Controller\AdminoverviewController::class,
        							'action'     => 'adminoverview',
        					],
        			],
        	],
        		
        		
        	'benutzertabelle' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/benutzertabelle[/:action]',
        					'defaults' => [
        							'controller' => Controller\BenutzertabelleController::class,
        							'action'     => 'benutzertabelle',
        					],
        			],
        	],
        		
        	
        		'registrieren' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/registrieren[/:action]',
        						'defaults' => [
        								'controller' => Controller\RegistrierenController::class,
        								'action'     => 'registrieren',
        						],
        				],
        		],
        		
        	'gruppeanlegen' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/gruppeanlegen[/:action]',
        					'defaults' => [
        							'controller' => Controller\GruppeanlegenController::class,
        							'action'     => 'gruppeanlegen',
        					],
        			],
        	],
        		
        		
        	'groupoverview' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/groupoverview[/:action]',
        					'defaults' => [
        							'controller' => Controller\GroupoverviewController::class,
        							'action'     => 'groupoverview',
        					],
        			],
        	],
        		
        	'groupshow' => [
        			'type'    => Segment::class,
       				'options' => [
       						'route'    => '/groupshow[/:action]',
       						'defaults' => [
       								'controller' => Controller\GroupshowController::class,
        							'action'     => 'groupshow',
        					],
       				],
       		],
        		
        	'groupedit' => [
        			'type'    => Segment::class,
       				'options' => [
       						'route'    => '/groupedit[/:action]',
       						'defaults' => [
       								'controller' => Controller\GroupeditController::class,
       								'action'     => 'groupedit',
       						],
       				],
       		],
        		
        	'groupdelete' => [
        			'type'    => Segment::class,
       				'options' => [
       						'route'    => '/groupdelete[/:action]',
       						'defaults' => [
       								'controller' => Controller\GroupdeleteController::class,
       								'action'     => 'groupdelete',
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
        		
        	'file' => [
        			'type'    => Segment::class,
       				'options' => [
       						'route'    => '/upload[/:action]',
       						'defaults' => [
       								'controller' => Controller\FileController::class,
       								'action'     => 'upload',
       						],
       				],
        		],
       	],
    		
    ],
		
 
		
		
		
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        	Controller\OverviewController::class => InvokableFactory::class,
        	Controller\GruppeanlegenController::class => InvokableFactory::class,
        	Controller\GroupoverviewController::class => InvokableFactory::class,
        	Controller\GroupshowController::class => InvokableFactory::class,
        	Controller\GroupeditController::class => InvokableFactory::class,
        	Controller\GroupdeleteController::class => InvokableFactory::class,
        	Controller\LoginController::class => InvokableFactory::class,
        	Controller\FileController::class => InvokableFactory::class,
           	Controller\HelpController::class => InvokableFactory::class,
        	Controller\RegistrierenController::class => InvokableFactory::class,
        	Controller\AdminoverviewController::class => InvokableFactory::class,
        	Controller\BenutzertabelleController::class => InvokableFactory::class,
        		
        		

        		
        	
        		
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
        	'application/adminoverview/adminoverview' => __DIR__ . '/../view/application/adminoverview/adminoverview.phtml',
        	'application/benutzertabelle/benutzertabelle' => __DIR__ . '/../view/application/benutzertabelle/benutzertabelle.phtml',
        	'application/registrieren/registrieren' => __DIR__ . '/../view/application/registrieren/registrieren.phtml',
        	'application/hilfe/hilfe' => __DIR__ . '/../view/application/hilfe/hilfe.phtml',
        	'application/gruppe/anlegen' => __DIR__ . '/../view/application/anlegen/anlegen.phtml',
        	'application/user/login' => __DIR__ . '/../view/application/login/login.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
