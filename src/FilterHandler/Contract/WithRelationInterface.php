<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\FilterHandler\Contract;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
interface WithRelationInterface
{
    public function relationJoin($relationJoins, $rootAlias, $rootClass, $relationsAndFieldName): array;
}
