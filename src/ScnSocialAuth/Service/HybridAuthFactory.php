<?php
/**
 * ScnSocialAuth Module
 *
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */

namespace ScnSocialAuth\Service;

use Hybrid_Auth;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Router\Http\TreeRouteStack;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */
class HybridAuthFactory implements FactoryInterface
{
    public function getBaseUrl(ContainerInterface $container)
    {
        $router = $container->get('Router');
        if (!$router instanceof TreeRouteStack) {
            throw new ServiceNotCreatedException('TreeRouteStack is required to create a fully qualified base url for HybridAuth');
        }

        $request = $container->get('Request');
        if (!$router->getRequestUri() && method_exists($request, 'getUri')) {
            $router->setRequestUri($request->getUri());
        }
        if (!$router->getBaseUrl() && method_exists($request, 'getBaseUrl')) {
            $router->setBaseUrl($request->getBaseUrl());
        }

        return $router->assemble(
            [],
            [
                'name' => 'scn-social-auth-hauth',
                'force_canonical' => true,
                'query' => ['utm_nooverride' => 1],
            ]
        );
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Making sure the SessionManager is initialized
        // before creating HybridAuth components
        $sessionManager = $container->get('ScnSocialAuth_ZendSessionManager');
        $sessionManager->start();

        /* @var $options \ScnSocialAuth\Options\ModuleOptions */
        $options = $container->get('ScnSocialAuth-ModuleOptions');

        $baseUrl = $this->getBaseUrl($container);

        $hybridAuth = new Hybrid_Auth(
            [
                'base_url' => $baseUrl,
                "debug_mode" => $options->getDebugMode(),
                "debug_file" => $options->getDebugFile(),
                'providers' => [
                    'BitBucket' => [
                        'enabled' => $options->getBitbucketEnabled(),
                        'keys' => [
                            'key' => $options->getBitbucketKey(),
                            'secret' => $options->getBitbucketSecret(),
                        ],
                        'scope' => '',
                        'wrapper' => [
                            'class' => 'Hybrid_Providers_BitBucket',
                            'path' => realpath(__DIR__ . '/../HybridAuth/Provider/BitBucket.php'),
                        ],
                    ],
                    'Facebook' => [
                        'enabled' => $options->getFacebookEnabled(),
                        'keys' => [
                            'id' => $options->getFacebookClientId(),
                            'secret' => $options->getFacebookSecret(),
                        ],
                        'scope' => $options->getFacebookScope(),
                        'display' => $options->getFacebookDisplay(),
                        'trustForwarded' => $options->getFacebookTrustForwarded(),
                    ],
                    'Foursquare' => [
                        'enabled' => $options->getFoursquareEnabled(),
                        'keys' => [
                            'id' => $options->getFoursquareClientId(),
                            'secret' => $options->getFoursquareSecret(),
                        ],
                    ],
                    'GitHub' => [
                        'enabled' => $options->getGithubEnabled(),
                        'keys' => [
                            'id' => $options->getGithubClientId(),
                            'secret' => $options->getGithubSecret(),
                        ],
                        'scope' => $options->getGithubScope(),
                        'wrapper' => [
                            'class' => 'Hybrid_Providers_GitHub',
                            'path' => realpath(__DIR__ . '/../HybridAuth/Provider/GitHub.php'),
                        ],
                    ],
                    'Google' => [
                        'enabled' => $options->getGoogleEnabled(),
                        'keys' => [
                            'id' => $options->getGoogleClientId(),
                            'secret' => $options->getGoogleSecret(),
                        ],
                        'scope' => $options->getGoogleScope(),
                        'hd' => $options->getGoogleHd(),
                    ],
                    'LinkedIn' => [
                        'enabled' => $options->getLinkedInEnabled(),
                        'keys' => [
                            'key' => $options->getLinkedInClientId(),
                            'secret' => $options->getLinkedInSecret(),
                        ],
                    ],
                    'Twitter' => [
                        'enabled' => $options->getTwitterEnabled(),
                        'keys' => [
                            'key' => $options->getTwitterConsumerKey(),
                            'secret' => $options->getTwitterConsumerSecret(),
                        ],
                    ],
                    'Yahoo' => [
                        'enabled' => $options->getYahooEnabled(),
                        'keys' => [
                            'key' => $options->getYahooClientId(),
                            'secret' => $options->getYahooSecret(),
                        ],
                    ],
                    'Tumblr' => [
                        'enabled' => $options->getTumblrEnabled(),
                        'keys' => [
                            'key' => $options->getTumblrConsumerKey(),
                            'secret' => $options->getTumblrConsumerSecret(),
                        ],
                        'wrapper' => [
                            'class' => 'Hybrid_Providers_Tumblr',
                            'path' => realpath(__DIR__ . '/../HybridAuth/Provider/Tumblr.php'),
                        ],
                    ],
                    'Mailru' => [
                        'enabled' => $options->getMailruEnabled(),
                        'keys' => [
                            'id' => $options->getMailruClientId(),
                            'secret' => $options->getMailruSecret(),
                        ],
                        'wrapper' => [
                            'class' => 'Hybrid_Providers_Mailru',
                            'path' => realpath(__DIR__ . '/../HybridAuth/Provider/Mailru.php'),
                        ],
                    ],
                    'Odnoklassniki' => [
                        'enabled' => $options->getOdnoklassnikiEnabled(),
                        'keys' => [
                            'id' => $options->getOdnoklassnikiAppId(),
                            'key' => $options->getOdnoklassnikiKey(),
                            'secret' => $options->getOdnoklassnikiSecret(),
                        ],
                        'wrapper' => [
                            'class' => 'Hybrid_Providers_Odnoklassniki',
                            'path' => realpath(__DIR__ . '/../HybridAuth/Provider/Odnoklassniki.php'),
                        ],
                    ],
                    'Vkontakte' => [
                        'enabled' => $options->getVkontakteEnabled(),
                        'keys' => [
                            'id' => $options->getVkontakteAppId(),
                            'secret' => $options->getVkontakteSecret(),
                        ],
                        'wrapper' => [
                            'class' => 'Hybrid_Providers_Vkontakte',
                            'path' => realpath(__DIR__ . '/../HybridAuth/Provider/Vkontakte.php'),
                        ],
                    ],
                    'Yandex' => [
                        'enabled' => $options->getYandexEnabled(),
                        'keys' => [
                            'id' => $options->getYandexAppId(),
                            'secret' => $options->getYandexSecret(),
                        ],
                        'wrapper' => [
                            'class' => 'Hybrid_Providers_Yandex',
                            'path' => realpath(__DIR__ . '/../HybridAuth/Provider/Yandex.php'),
                        ],
                    ],
                    'Instagram' => [
                        'enabled' => $options->getInstagramEnabled(),
                        'keys' => [
                            'id' => $options->getInstagramClientId(),
                            'secret' => $options->getInstagramClientSecret(),
                        ],
                        'wrapper' => [
                            'class' => 'Hybrid_Providers_Instagram',
                            'path' => realpath(__DIR__ . '/../HybridAuth/Provider/Instagram.php'),
                        ],
                    ],
                ],
            ]
        );

        return $hybridAuth;
    }
}
