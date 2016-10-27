<?php

namespace ScnSocialAuth;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            'ScnSocialAuth-HybridAuth' => Service\HybridAuthControllerFactory::class,
            'ScnSocialAuth-User' => Service\UserControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'aliases' => [
            'scnsocialauthprovider' => Controller\Plugin\ScnSocialAuthProvider::class,
            'scnSocialAuthProvider' => Controller\Plugin\ScnSocialAuthProvider::class,
        ],
        'factories' => [
            Controller\Plugin\ScnSocialAuthProvider::class => Service\ScnSocialAuthProviderFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'scn-social-auth-hauth' => [
                'type'    => 'Literal',
                'priority' => 2000,
                'options' => [
                    'route' => '/scn-social-auth/hauth',
                    'defaults' => [
                        'controller' => 'ScnSocialAuth-HybridAuth',
                        'action'     => 'index',
                    ],
                ],
            ],
            'scn-social-auth-user' => [
                'type' => 'Literal',
                'priority' => 2000,
                'options' => [
                    'route' => '/user',
                    'defaults' => [
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'authenticate' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/authenticate',
                            'defaults' => [
                                'controller' => 'zfcuser',
                                'action'     => 'authenticate',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'provider' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/:provider',
                                    'constraints' => [
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'ScnSocialAuth-User',
                                        'action' => 'provider-authenticate',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'login' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/login',
                            'defaults' => [
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'login',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'provider' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/:provider',
                                    'constraints' => [
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'ScnSocialAuth-User',
                                        'action' => 'provider-login',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'logout' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/logout',
                            'defaults' => [
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'logout',
                            ],
                        ],
                    ],
                    'register' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/register',
                            'defaults' => [
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'register',
                            ],
                        ],
                    ],
                    'add-provider' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/add-provider',
                            'defaults' => [
                                'controller' => 'ScnSocialAuth-User',
                                'action'     => 'add-provider',
                            ],
                        ],
                        'child_routes' => [
                            'provider' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/:provider',
                                    'constraints' => [
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'ScnSocialAuth_ZendDbAdapter' => 'Zend\Db\Adapter\Adapter',
            'ScnSocialAuth_ZendSessionManager' => 'Zend\Session\SessionManager',
        ],
        'factories' => [
            'HybridAuth' => Service\HybridAuthFactory::class,
            'ScnSocialAuth-ModuleOptions' => Service\ModuleOptionsFactory::class,
            'ScnSocialAuth-UserProviderMapper' => Service\UserProviderMapperFactory::class,
            'ScnSocialAuth-AuthenticationAdapterChain' => Service\AuthenticationAdapterChainFactory::class,
            Authentication\Adapter\HybridAuth::class => Service\HybridAuthAdapterFactory::class,
            'zfcuser_redirect_callback' => Service\RedirectCallbackFactory::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'socialSignInButton' => View\Helper\SocialSignInButton::class,
        ],
        'factories' => [
            View\Helper\SocialSignInButton::class => InvokableFactory::class,
            'scnUserProvider'   => Service\UserProviderViewHelperFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'scn-social-auth' => __DIR__ . '/../view'
        ],
    ],
];
