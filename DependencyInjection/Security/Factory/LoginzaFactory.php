<?php
namespace Zim32\LoginzaBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LoginzaFactory extends AbstractFactory {

	public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint){
        $result = parent::create($container, $id, $config, $userProvider, $defaultEntryPoint);
        $d = $container->getDefinition($this->getListenerId().'.'.$id);
        $d->addMethodCall('setKeys', array(
            isset($config['secret_key']) ? $config['secret_key'] : null,
            isset($config['widget_id']) ? $config['widget_id'] : null,
        ));
        return $result;
        //$this->addOption('create_user_if_not_exists', false);
        //$this->addOption('secret_key', $config['secret_key']);
        //$this->addOption('widget_id', $config['widget_id']);
        //$container->setParameter('security.loginza.secret_key', $config['secret_key']);
        //$container->setParameter('security.loginza.widget_id', $config['widget_id']);
        /*$container->setParameter('security.loginza.login_route', $config['login_route']);
        $container->setParameter('security.loginza.token_route', $config['token_route']);
        $container->setParameter('security.loginza.entity', isset($config['entity'])?$config['entity']:false);*/
	}

    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        if (null !== $defaultEntryPoint) {
            return $defaultEntryPoint;
        }
        $entryPointId = 'security.authentication.entry_point.loginza.'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('security.authentication.entry_point.loginza'))
            ->addArgument($config)
        ;

        return $entryPointId;
    }

    public function addConfiguration(NodeDefinition $node)
    {
        parent::addConfiguration($node);
        $node
            /*->children()
                ->scalarNode('login_route')->isRequired()->end()
            ->end()*/
            ->children()
                ->scalarNode('user_provider')->isRequired()->end()
            ->end()
            ->children()
                ->scalarNode('secret_key')->end()
            ->end()
            ->children()
                ->scalarNode('widget_id')->end()
            ->end()
            /*->children()
                ->scalarNode('entity')->end()
            ->end()*/
           ;
	}

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'loginza';
    }

	protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId){
        $providerId = 'security.authentication.provider.loginza.'.$id;
        $provider = $container
            ->setDefinition($providerId, new DefinitionDecorator('security.authentication.provider.loginza'))
            ->replaceArgument(1, $config['user_provider']);
        return $providerId;
	}

    /**
     * @return string
     */
    protected function getListenerId()
    {
        return 'security.authentication.listener.loginza';
    }
}
