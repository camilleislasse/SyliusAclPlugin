<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('bitbag_sylius_acl');
        $rootNode = $treeBuilder->getRootNode();

        $this->addPermissionsSection($rootNode);

        return $treeBuilder;
    }

    private function addPermissionsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('permissions')
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('label')->defaultNull()->end()
                        ->scalarNode('parent')->defaultNull()->end()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
