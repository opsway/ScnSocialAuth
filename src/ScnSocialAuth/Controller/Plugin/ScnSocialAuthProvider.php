<?php

namespace ScnSocialAuth\Controller\Plugin;

use ScnSocialAuth\Mapper\UserProviderInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use ZfcUser\Entity\UserInterface;

class ScnSocialAuthProvider extends AbstractPlugin
{
    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * @var UserProviderInterface
     */
    protected $mapper;

    /**
     * Returns a UserProviderInterface for $user and $provider
     *
     * @param UserInterface $user
     * @param string        $provider
     */
    public function getProvider(UserInterface $user, $provider)
    {
        return $this->getMapper()->findProviderByUser($user, $provider);
    }

    /**
     * Returns an array of UserProviderInterface for $user
     *
     * @param UserInterface $user
     */
    public function getProviders(UserInterface $user)
    {
        return $this->getMapper()->findProvidersByUser($user);
    }

    /**
     * set mapper
     *
     * @param  UserProviderInterface $mapper
     * @return $this
     */
    public function setMapper(UserProviderInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * get mapper
     *
     * @return UserProviderInterface
     */
    public function getMapper()
    {
        if (!$this->mapper instanceof UserProviderInterface) {
            throw new \RuntimeException("ScnSocialAuthProvider plugin should be inject UserProviderInterface mapper.");
        }

        return $this->mapper;
    }
}
