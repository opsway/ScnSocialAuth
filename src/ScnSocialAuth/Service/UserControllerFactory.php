<?php
/**
 * ScnSocialAuth Module
 *
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */

namespace ScnSocialAuth\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use ScnSocialAuth\Controller\UserController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */
class UserControllerFactory implements FactoryInterface
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
        $mapper = $container->get('ScnSocialAuth-UserProviderMapper');
        $moduleOptions = $container->get('ScnSocialAuth-ModuleOptions');
        $redirectCallback = $container->get('zfcuser_redirect_callback');
        $zfcuserModuleOptions = $container->get('zfcuser_module_options');

        $controller = new UserController($redirectCallback, $container);
        $controller->setMapper($mapper);
        $controller->setOptions($moduleOptions);
        $controller->setZfcModuleOptions($zfcuserModuleOptions);

        try {
          $hybridAuth = $container->get('HybridAuth');
          $controller->setHybridAuth($hybridAuth);
        } catch (ServiceNotCreatedException $e) {
          // This is likely the user cancelling login...
        }

        return $controller;
    }
}
