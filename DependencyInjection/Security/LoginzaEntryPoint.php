<?php

namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class LoginzaEntryPoint implements AuthenticationEntryPointInterface {

    protected $config = array();
    protected $container;

    public function __construct(ContainerInterface $container, array $conf){
        $this->config = $conf;
        $this->container = $container;
    }


    public function start(Request $request, AuthenticationException $authException = null){
        if($authException !== null){
            if(!is_a($authException, 'Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException')){
                $this->container->get('session')->setFlash('error', $authException->getMessage());
            }
        }
        return new RedirectResponse($this->config['login_path']);
     }
}