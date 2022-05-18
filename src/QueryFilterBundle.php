<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle;

use Bugloos\QueryFilterBundle\DependencyInjection\QueryFilterExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class QueryFilterBundle extends Bundle
{
    /**
     * @return ExtensionInterface|QueryFilterExtension
     *
     * @author Milad Ghofrani <milad.g@bugloos.com>
     */
    public function getContainerExtension(): QueryFilterExtension|ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new QueryFilterExtension();
        }

        return $this->extension;
    }
}
