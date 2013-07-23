<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 17.07.13
 * Time: 15:22
 */

namespace Ivan1986\LoginzaBundle\DependencyInjection\Security;


use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class LoginzaUserProvider implements LoginzaUserProviderInterface {

    abstract public function loadUserByIdentityAndProvider($identity, $provider, $loginza_info);

    public function loadUserByUsername($username)
    {
        throw new \Exception("Not supported");
    }

    abstract public function refreshUser(UserInterface $user);

    public function supportsClass($class)
    {
        return true;
    }

}
