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
        		
        	'statistiken' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/statistiken[/:action]',
        					'defaults' => [
        							'controller' => Controller\StatistikenController::class,
        							'action'     => 'statistiken',
        					],
        			],
        	],
        		
        	'zahlunganzeigen' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/zahlunganzeigen[/:action]',
        					'defaults' => [
        							'controller' => Controller\ZahlunganzeigenController::class,
        							'action'     => 'zahlunganzeigen',
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
        		
        		
        		'confirm' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/confirm[/:action]',
        						'defaults' => [
        								'controller' => Controller\ConfirmController::class,
        								'action'     => 'confirm',
        						],
        				],
        		],
        		
        	
        		
       		'benutzerdeaktivieren' => [
       				'type'    => Segment::class,
       				'options' => [
       						'route'    => '/benutzerdeaktivieren[/:action]',
       						'defaults' => [
       								'controller' => Controller\BenutzerdeaktivierenController::class,
        							'action'     => 'benutzerdeaktivieren',
        					],
        			],
        	],
        		
        	'benutzerreaktivieren' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/benutzerreaktivieren[/:action]',
        					'defaults' => [
        							'controller' => Controller\BenutzerreaktivierenController::class,
        							'action'     => 'benutzerreaktivieren',
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
        		
        		
        		
        		'usersuchen' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/usersuchen[/:action]',
        						'defaults' => [
        								'controller' => Controller\UsersuchenController::class,
        								'action'     => 'usersuchen',
        						],
        				],
        		],
        		
        		'passwortvergessen' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/passwortvergessen[/:action]',
        						'defaults' => [
        								'controller' => Controller\PasswortvergessenController::class,
        								'action'     => 'passwortvergessen',
        						],
        				],
        		],
        		
        		
        		
        		'teilnehmersuchetabelle' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/teilnehmersuchetabelle[/:action]',
        						'defaults' => [
        								'controller' => Controller\UsersuchenController::class,
        								'action'     => 'usersuchen',
        						],
        				],
        		],
        		
        		
        		
        		'emailpasswort' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/emailpasswort[/:action]',
        						'defaults' => [
        								'controller' => Controller\EmailpasswortController::class,
        								'action'     => 'emailpasswort',
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
        		
        		'kategorieanlegen' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/kategorieanlegen[/:action]',
        						'defaults' => [
        								'controller' => Controller\KategorieanlegenController::class,
        								'action'     => 'kategorieanlegen',
        						],
        				],
        		],
        		
        		
        		
        		'kategorieedit' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/kategorieedit[/:action]',
        						'defaults' => [
        								'controller' => Controller\KategorieeditController::class,
        								'action'     => 'kategorieedit',
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
        		
        	'gruppenverlauf' => [
        			'type'    => Segment::class,
        			'options' => [
        					'route'    => '/gruppenverlauf[/:action]',
        					'defaults' => [
        							'controller' => Controller\GruppenverlaufController::class,
        							'action'     => 'gruppenverlauf',
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
        
        		
        	'gruppeverlassen' => [
        			'type'    => Segment::class,
       				'options' => [
       						'route'    => '/gruppeverlassen[/:action]',
       						'defaults' => [
       								'controller' => Controller\GruppeverlassenController::class,
       								'action'     => 'gruppeverlassen',
       						],
       				],
       		],
        		
        		
        		'einladungannehmen' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/einladungannehmen[/:action]',
        						'defaults' => [
        								'controller' => Controller\EinladungannehmenController::class,
        								'action'     => 'einladungannehmen',
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
        		
        		
        		'kategorien' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/kategorien[/:action]',
        						'defaults' => [
        								'controller' => Controller\KategorienController::class,
        								'action'     => 'kategorien',
        						],
        				],
        		],
        		
        		'zahlunganlegen' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/zahlunganlegen[/:action]',
        						'defaults' => [
        								'controller' => Controller\ZahlunganlegenController::class,
        								'action'     => 'zahlunganlegen',
        						],
        				],
        		],
        		
        		'zahlunganzeigen' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/zahlunganzeigen[/:action]',
        						'defaults' => [
        								'controller' => Controller\ZahlunganzeigenController::class,
        								'action'     => 'zahlunganzeigen',
        						],
        				],
        		],
        		
        		'groupcsv' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/groupcsv[/:action]',
        						'defaults' => [
        								'controller' => Controller\GroupcsvController::class,
        								'action'     => 'groupcsv',
        						],
        				],
        		],
        		
        		'zahlungbearbeiten' => [
        				'type'    => Segment::class,
        				'options' => [
        						'route'    => '/zahlungbearbeiten[/:action]',
        						'defaults' => [
        								'controller' => Controller\ZahlungbearbeitenController::class,
        								'action'     => 'zahlungbearbeiten',
        						],
        				],
        		],
        		
        		
        		
        		
        		
        	'profil' => [
        			'type'    => Segment::class,
       				'options' => [
       						'route'    => '/profil[/:action]',
       						'defaults' => [
       								'controller' => Controller\ProfilController::class,
       								'action'     => 'profil',
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
        	Controller\GruppeverlassenController::class => InvokableFactory::class,
        	Controller\EinladungannehmenController::class => InvokableFactory::class,
        	Controller\LoginController::class => InvokableFactory::class,
        	Controller\ProfilController::class => InvokableFactory::class,
           	Controller\HelpController::class => InvokableFactory::class,
        	Controller\RegistrierenController::class => InvokableFactory::class,
        	Controller\AdminoverviewController::class => InvokableFactory::class,
        	Controller\BenutzertabelleController::class => InvokableFactory::class,
        	Controller\KategorienController::class => InvokableFactory::class,
        	Controller\BenutzerdeaktivierenController::class => InvokableFactory::class,
        	Controller\BenutzerreaktivierenController::class => InvokableFactory::class,
        	Controller\KategorieanlegenController::class => InvokableFactory::class,
        	Controller\KategorieeditController::class => InvokableFactory::class,
        	Controller\UsersuchenController::class => InvokableFactory::class,
        	Controller\PasswortvergessenController::class => InvokableFactory::class,
        	Controller\EmailpasswortController::class => InvokableFactory::class,
			Controller\ZahlunganlegenController::class => InvokableFactory::class,
        	Controller\ZahlunganzeigenController::class => InvokableFactory::class,
        	Controller\ConfirmController::class => InvokableFactory::class,
        	Controller\StatistikenController::class => InvokableFactory::class,
        	Controller\GroupcsvController::class => InvokableFactory::class,
        	Controller\ZahlunganzeigenController::class => InvokableFactory::class,
        	Controller\GruppenverlaufController::class => InvokableFactory::class,
        	Controller\ZahlungbearbeitenController::class => InvokableFactory::class,
        	
        		
        		
        		
        		
        		
        		
        		


        		
        	
        		
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
