<?php

namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;

interface LoginzaUserProviderInterface extends UserProviderInterface {

    public function loadUserByIdentityAndProvider($identity, $provider);

}