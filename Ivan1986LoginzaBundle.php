<?php

namespace Ivan1986\LoginzaBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Ivan1986\LoginzaBundle\DependencyInjection\Security\Factory\LoginzaFactory;

class Ivan1986LoginzaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new LoginzaFactory());
    }
}
