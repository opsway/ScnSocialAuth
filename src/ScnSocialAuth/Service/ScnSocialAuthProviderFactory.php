<?php
/**
 * Created by PhpStorm.
 * User: Shandy
 * Date: 27.10.2016
 * Time: 17:44
 */

namespace ScnSocialAuth\Service;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use ScnSocialAuth\Controller\Plugin\ScnSocialAuthProvider;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ScnSocialAuthProviderFactory implements FactoryInterface
{
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
        $plugin = new ScnSocialAuthProvider();
        $plugin->setMapper($container->get('ScnSocialAuth-UserProviderMapper'));
        return $plugin;
    }
}
