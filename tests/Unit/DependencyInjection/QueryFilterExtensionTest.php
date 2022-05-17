<?php

namespace Bugloos\QueryFilterBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Bugloos\QueryFilterBundle\DependencyInjection\QueryFilterExtension;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
final class QueryFilterExtensionTest extends AbstractExtensionTestCase
{
    public function test_default_config(): void
    {
        $this->load();

        $this->assertTrue($this->container->getDefinition('bugloos_query_filter.query_filter')->isPublic());
        $this->assertNotEmpty($this->container->getDefinition('bugloos_query_filter.query_filter')->getArguments());

        $this->assertFalse($this->container->getDefinition('bugloos_query_filter.filter_handler_factory.filter_factory')->isPublic());
        $this->assertNotEmpty($this->container->getDefinition('bugloos_query_filter.filter_handler_factory.filter_factory')->getArguments());
    }

    protected function getContainerExtensions(): array
    {
        return [new QueryFilterExtension()];
    }
}
