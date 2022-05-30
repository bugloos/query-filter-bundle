<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler\Traits;

use Bugloos\QueryFilterBundle\Enum\StrategyType;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
trait TypeTrait
{
    public function filterWhereClause($alias, $field, $filterParameter, $strategy, $value): string
    {
        $strategy = $this->strategy($strategy);
        $this->validateStrategy($strategy);

        return sprintf(
            '%s.%s %s :%s',
            $alias,
            $field,
            StrategyType::strategySign($strategy),
            $filterParameter
        );
    }
}
