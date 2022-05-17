<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler;

use Bugloos\QueryFilterBundle\Enum\StrategyType;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\AbstractTypeHandler;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\FilterValueInterface;
use Bugloos\QueryFilterBundle\TypeHandler\Traits\TypeTrait;
use Carbon\Carbon;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class DateHandler extends AbstractTypeHandler implements FilterValueInterface
{
    use TypeTrait;

    public function filterValue($value, $strategy)
    {
        if ('now' === $value) {
            return Carbon::now();
        }

        return $value;
    }

    public function allowStrategies(): array
    {
        return [
            StrategyType::EXACT,
            StrategyType::AFTER,
            StrategyType::BEFORE,
        ];
    }

    protected function strategy($strategy): string
    {
        return $strategy ?: StrategyType::AFTER;
    }
}
