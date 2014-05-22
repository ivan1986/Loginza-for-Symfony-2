README
======

This bundle is rewrited Zim32LoginzaBundle.
https://github.com/zim32/Loginza-for-Symfony-2

1) install - standart by composer

    "ivan1986/loginza-bundle": "dev-master",

    new Ivan1986\LoginzaBundle\Ivan1986LoginzaBundle(),
    
-----------------

2) Configure /app/config/security.yml
	  
    providers:
      loginza:
        id: <id for you user provider>
		
    firewalls:
      <name>:
        pattern:  ^/
        loginza:
          provider: loginza
          check_path: <>
          <all form options>

-----------------

3) Create form for loginza whis token_url={{check_path|url_encode}}

    {% extends "::base.html.twig" %}
    
    {% block body %}
    	<script src="//loginza.ru/js/widget.js" type="text/javascript"></script>
        <a href="https://loginza.ru/api/widget?token_url={{check_path|url_encode}}" class="loginza">Please login</a>
    {% endblock %}

-----------------

4) Create user provider - it must implements LoginzaUserProviderInterface ( extends LoginzaUserProvider for example)
and implements 

    public function loadUserByIdentityAndProvider($identity, $provider, $loginza_info)
    
User has ROLE_LOGINZA_USER role after authentication
