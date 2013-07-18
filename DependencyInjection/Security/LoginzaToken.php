<?php

namespace Ivan1986\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class LoginzaToken extends AbstractToken {

    public $loginza_info;

    public function __construct(array $roles, $loginza_info){
        if(!isset($roles['ROLE_LOGINZA_USER'])) $roles[] = 'ROLE_LOGINZA_USER';
        parent::__construct($roles);
        $this->loginza_info = $loginza_info;
    }

    public function getCredentials(){
        return array();
    }

    public function getUid(){
        $info = $this->getAttribute('loginza_info');
        if(!isset($info['uid'])) throw new \Exception("User id is empty");
        return $info['uid'];
    }
}
