<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle;

use Bugloos\QueryFilterBundle\DependencyInjection\QueryFilterExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class QueryFilterBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new QueryFilterExtension();
        }

        return $this->extension;
    }
}
