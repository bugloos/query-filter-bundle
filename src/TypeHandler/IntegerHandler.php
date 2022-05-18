<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler;

use Bugloos\QueryFilterBundle\Enum\StrategyType;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\AbstractTypeHandler;
use Bugloos\QueryFilterBundle\TypeHandler\Traits\TypeTrait;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class IntegerHandler extends AbstractTypeHandler
{
    use TypeTrait;

    public function allowStrategies(): array
    {
        return [
            StrategyType::EXACT,
            StrategyType::AFTER,
            StrategyType::AFTER_EXACT,
            StrategyType::BEFORE,
            StrategyType::BEFORE_EXACT,
        ];
    }

    protected function strategy($strategy): string
    {
        return $strategy ?: StrategyType::EXACT;
    }
}
