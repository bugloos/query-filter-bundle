<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler;

use Bugloos\QueryFilterBundle\Enum\StrategyType;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\AbstractTypeHandler;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class ArrayHandler extends AbstractTypeHandler
{
    public function filterWhereClause($alias, $field, $filterParameter, $strategy, $value): string
    {
        return sprintf(
            '%s.%s %s (:%s)',
            $alias,
            $field,
            StrategyType::strategySign(StrategyType::ARRAY),
            $filterParameter
        );
    }

    public function allowStrategies(): array
    {
        return [
            StrategyType::ARRAY,
        ];
    }

    protected function strategy($strategy): string
    {
        return StrategyType::ARRAY;
    }
}
