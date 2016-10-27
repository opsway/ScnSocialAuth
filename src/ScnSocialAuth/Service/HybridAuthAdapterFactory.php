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
use ScnSocialAuth\Authentication\Adapter\HybridAuth as HybridAuthAdapter;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */
class HybridAuthAdapterFactory implements FactoryInterface
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
        $moduleOptions = $container->get('ScnSocialAuth-ModuleOptions');
        $zfcUserOptions = $container->get('zfcuser_module_options');

        $mapper = $container->get('ScnSocialAuth-UserProviderMapper');
        $zfcUserMapper = $container->get('zfcuser_user_mapper');

        $adapter = new HybridAuthAdapter();
        $adapter->setOptions($moduleOptions);
        $adapter->setZfcUserOptions($zfcUserOptions);
        $adapter->setMapper($mapper);
        $adapter->setZfcUserMapper($zfcUserMapper);
        $adapter->setServiceManager($container);

        return $adapter;
    }
}
