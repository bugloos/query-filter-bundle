<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler\Contract;

use Bugloos\QueryFilterBundle\Enum\StrategyType;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
abstract class AbstractTypeHandler
{
    abstract public function filterWhereClause($alias, $field, $filterParameter, $strategy, $value): string;

    abstract protected function allowStrategies(): array;

    abstract protected function strategy($strategy): string;

    protected function validateStrategy($strategy): void
    {
        if (!\in_array($strategy, $this->allowStrategies(), true)) {
            StrategyType::createInvalidArgumentException($strategy);
        }
    }
}
