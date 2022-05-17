<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Exception;
use Bugloos\QueryFilterBundle\QueryFilterBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Zenstruck\Foundry\ZenstruckFoundryBundle;

class QueryFilterTestKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new ZenstruckFoundryBundle(),
            new DoctrineBundle(),
            new FrameworkBundle(),
            new QueryFilterBundle(),
        ];
    }

    /**
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/../src/Resources/config/services.xml');
        $loader->load(__DIR__.'/Fixtures/Config/services.yaml');
        $loader->load(__DIR__.'/Fixtures/Config/config.yaml');
    }
}
