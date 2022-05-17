<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('query_filter');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->integerNode('default_cache_time')->defaultValue(3600)->end()
            ->scalarNode('separator')->defaultValue('.')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
