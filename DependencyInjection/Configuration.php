<?php

namespace Arkounay\BlockBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     * Available options :
     *      - roles: []
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('block_bundle');
        $rootNode
            ->children()
                ->arrayNode('roles')
                    ->prototype('scalar')
                    ->end()
                ->defaultValue(['ROLE_ADMIN'])
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
