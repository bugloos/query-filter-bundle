<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\DependencyInjection;

use http\Exception\RuntimeException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class QueryFilterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);

        if (!isset($configuration) || empty($configuration)) {
            throw new RuntimeException(
                'The configuration is not set correctly'
            );
        }

        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $definition = $container->getDefinition('bugloos_query_filter.query_filter');
        $definition->setArgument(3, $config['default_cache_time']);
        $definition->setArgument(4, $config['separator']);
    }
}
