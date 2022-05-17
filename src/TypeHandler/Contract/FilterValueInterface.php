<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler\Contract;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
interface FilterValueInterface
{
    public function filterValue($value, $strategy);
}
