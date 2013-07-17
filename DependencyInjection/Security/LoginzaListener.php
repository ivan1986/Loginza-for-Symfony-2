<?php

namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\DependencyInjection\Container;

class LoginzaListener extends AbstractAuthenticationListener {

    private $secret_key;
    private $widget_id;

    /**
     * Performs authentication.
     *
     * @param Request $request A Request instance
     *
     * @return TokenInterface|Response|null The authenticated token, null if full authentication is not possible, or a Response
     *
     * @throws AuthenticationException if the authentication fails
     */
    protected function attemptAuthentication(Request $request)
    {
        if($request->request->has('token') === false)
        {
            return null;
        }

        $loginzaToken = $request->request->get('token');

        if ($this->secret_key && $this->widget_id)
        {
            $signature = md5($loginzaToken.$this->secret_key);
            $ch = curl_init("http://loginza.ru/api/authinfo?token={$loginzaToken}&id={$this->widget_id}&sig={$signature}");
        }
        else
            $ch = curl_init("http://loginza.ru/api/authinfo?token={$loginzaToken}");
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $decoded = json_decode($result,true);
        if(empty($decoded)) throw new AuthenticationException("Wrong loginza responce format");
        if(isset($decoded['error_message'])) throw new AuthenticationException($decoded['error_message']);

        $token = new LoginzaToken(array(), $decoded);

        try {
            return $this->authenticationManager->authenticate($token);
        } catch (AuthenticationException $e) {
            $e->setToken($token);
            throw $e;
        }
    }

    public function setKeys($secret_key, $widget_id)
    {
        $this->secret_key = $secret_key;
        $this->widget_id = $widget_id;
    }

}
