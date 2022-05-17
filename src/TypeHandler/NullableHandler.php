<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler;

use Bugloos\QueryFilterBundle\Enum\StrategyType;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\AbstractTypeHandler;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class NullableHandler extends AbstractTypeHandler
{
    public function filterWhereClause($alias, $field, $filterParameter, $strategy): string
    {
        $strategy = $this->strategy($strategy);
        $this->validateStrategy($strategy);

        return sprintf(
            '%s.%s %s',
            $alias,
            $field,
            StrategyType::strategySign($strategy)
        );
    }

    public function allowStrategies(): array
    {
        return [
            StrategyType::IS_NULL => 'IS NULL',
            StrategyType::IS_NOT_NULL => 'IS NOT NULL',
        ];
    }

    protected function strategy($strategy): string
    {
        return $strategy ?: StrategyType::IS_NULL;
    }
}
