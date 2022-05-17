<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler;

use Bugloos\QueryFilterBundle\Enum\StrategyType;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\AbstractTypeHandler;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\FilterValueInterface;
use Bugloos\QueryFilterBundle\TypeHandler\Traits\TypeTrait;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class StringHandler extends AbstractTypeHandler implements FilterValueInterface
{
    use TypeTrait;

    public function filterValue($value, $strategy): string
    {
        $strategy = $this->strategy($strategy);
        $this->validateStrategy($strategy);

        return StrategyType::strategyValue($strategy, $value);
    }

    public function allowStrategies(): array
    {
        return [
            StrategyType::EXACT,
            StrategyType::PARTIAL,
            StrategyType::START,
            StrategyType::END,
            StrategyType::WORD_START,
        ];
    }

    protected function strategy($strategy): string
    {
        return $strategy ?: StrategyType::PARTIAL;
    }
}
