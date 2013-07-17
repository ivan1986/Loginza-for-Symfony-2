<?php
namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

class LoginzaAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var null|LoginzaUserProviderInterface
     */
    protected $userProvider;

    /**
     * @param null|\Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param null|\Symfony\Component\Security\Core\User\UserCheckerInterface $userChecker
     * @param bool $createIfNotExists
     */
    public function __construct($id, UserProviderInterface $userProvider = null, UserCheckerInterface $userChecker = null)
    {
        if (null === $userProvider) {
            throw new \InvalidArgumentException('$userProvider cannot be null.');
        }

        if (!($userProvider instanceof LoginzaUserProviderInterface)) {
            throw new \InvalidArgumentException('The $userProvider must implement LoginzaUserProviderInterface.');
        }

        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        if ($token instanceof LoginzaToken)
        {
            if ($token->getUser() instanceof UserInterface) {
                return $token;
            }
            $token->loginza_info;

            try {
                $user = $this->userProvider->loadUserByIdentityAndProvider(
                    $token->loginza_info['identity'],
                    $token->loginza_info['provider'],
                    $token->loginza_info
                );

                $newToken = new LoginzaToken($token->getRoles(), $token->loginza_info);
                $newToken->setUser($user);
                $newToken->setAuthenticated(true);
                return $newToken;
            } catch (AuthenticationException $e) {
                throw $e;
            } catch (\Exception $e) {
                throw new AuthenticationServiceException($e->getMessage(), (int) $e->getCode(), $e);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof LoginzaToken;
    }

}