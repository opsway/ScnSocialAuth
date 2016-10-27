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
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcUser\Authentication\Adapter\AdapterChainServiceFactory;

/**
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */
class AuthenticationAdapterChainFactory implements FactoryInterface
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
        // Temporarily replace the adapters in the module options with the HybridAuth adapter
        $zfcUserModuleOptions = $container->get('zfcuser_module_options');
        $currentAuthAdapters = $zfcUserModuleOptions->getAuthAdapters();
        $zfcUserModuleOptions->setAuthAdapters(array(100 => 'ScnSocialAuth\Authentication\Adapter\HybridAuth'));

        // Create a new adapter chain with HybridAuth adapter
        $factory = new AdapterChainServiceFactory();
        $chain = $factory($container, null);

        // Reset the adapters in the module options
        $zfcUserModuleOptions->setAuthAdapters($currentAuthAdapters);

        return $chain;
    }
}
