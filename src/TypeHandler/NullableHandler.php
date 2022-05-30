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
    public function filterWhereClause($alias, $field, $filterParameter, $strategy, $value): string
    {
        $strategy = $this->strategy($strategy);
        $this->validateStrategy($strategy);

        $strategyValue = StrategyType::strategySign($strategy);

        if($strategy === StrategyType::EXACT){
            $strategyValue = $value === 1
                ? StrategyType::strategySign(StrategyType::IS_NOT_NULL)
                : StrategyType::strategySign(StrategyType::IS_NULL);
        }

        return sprintf(
            '%s.%s %s',
            $alias,
            $field,
            $strategyValue
        );
    }

    public function allowStrategies(): array
    {
        return [
            StrategyType::EXACT,
            StrategyType::IS_NULL,
            StrategyType::IS_NOT_NULL,
        ];
    }

    protected function strategy($strategy): string
    {
        return $strategy ?: StrategyType::IS_NULL;
    }
}
