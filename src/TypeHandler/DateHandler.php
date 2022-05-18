<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler;

use Bugloos\QueryFilterBundle\Enum\StrategyType;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\AbstractTypeHandler;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\FilterValueInterface;
use Bugloos\QueryFilterBundle\TypeHandler\Traits\TypeTrait;
use DateTime;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class DateHandler extends AbstractTypeHandler implements FilterValueInterface
{
    use TypeTrait;

    public function filterValue($value, $strategy)
    {
        if ('now' === $value) {
            return date('Y-m-d H:i:s');
        }

        if ($this->isOnlyDate($value)) {
            if($strategy === 'after' || $strategy === 'after_exact') {
                $value .= " 00:00:00";
            }
            if($strategy === 'before' || $strategy === 'before_exact') {
                $value .= " 23:59:59";
            }
            return $value;
        }

        return $value;
    }

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

    private function isOnlyDate($date, $format = 'Y-m-d'): bool
    {
        $dt = DateTime::createFromFormat($format, $date);
        return $dt && $dt->format($format) === $date;
    }
}
