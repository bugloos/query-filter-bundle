<?php

namespace Bugloos\QueryFilterBundle\Tests\Unit\Enum;

use Bugloos\QueryFilterBundle\Enum\StrategyType;
use PHPUnit\Framework\TestCase;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class StrategyTypeTest extends TestCase
{
    public function test_exact_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::EXACT, $oldValue);

        self::assertSame($oldValue, $changedValue);
    }

    public function test_partial_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::PARTIAL, $oldValue);

        self::assertSame('%'.$oldValue.'%', $changedValue);
    }

    public function test_start_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::START, $oldValue);

        self::assertSame($oldValue.'%', $changedValue);
    }

    public function test_end_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::END, $oldValue);

        self::assertSame('%'.$oldValue, $changedValue);
    }

    public function test_word_start_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::WORD_START, $oldValue);

        self::assertSame('% '.$oldValue.'%', $changedValue);
    }

    public function test_array_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::ARRAY, $oldValue);

        self::assertSame($oldValue, $changedValue);
    }

    public function test_after_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::AFTER, $oldValue);

        self::assertSame($oldValue, $changedValue);
    }

    public function test_after_exact_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::AFTER_EXACT, $oldValue);

        self::assertSame($oldValue, $changedValue);
    }

    public function test_before_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::BEFORE, $oldValue);

        self::assertSame($oldValue, $changedValue);
    }

    public function test_before_exact_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::BEFORE_EXACT, $oldValue);

        self::assertSame($oldValue, $changedValue);
    }

    public function test_is_null_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::IS_NULL, $oldValue);

        self::assertSame($oldValue, $changedValue);
    }

    public function test_is_not_null_strategy_value(): void
    {
        $oldValue = 'test';
        $changedValue = StrategyType::strategyValue(StrategyType::IS_NOT_NULL, $oldValue);

        self::assertSame($oldValue, $changedValue);
    }

    public function test_wrong_strategy_value(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $oldValue = 'test';
        StrategyType::strategyValue('wrong', $oldValue);
    }

    public function test_exact_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::EXACT);

        self::assertSame('=', $sign);
    }

    public function test_partial_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::PARTIAL);

        self::assertSame('LIKE', $sign);
    }

    public function test_start_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::START);

        self::assertSame('LIKE', $sign);
    }

    public function test_end_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::END);

        self::assertSame('LIKE', $sign);
    }

    public function test_word_start_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::WORD_START);

        self::assertSame('LIKE', $sign);
    }

    public function test_array_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::ARRAY);

        self::assertSame('IN', $sign);
    }

    public function test_after_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::AFTER);

        self::assertSame('>', $sign);
    }

    public function test_before_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::BEFORE);

        self::assertSame('<', $sign);
    }

    public function test_is_null_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::IS_NULL);

        self::assertSame('IS NULL', $sign);
    }

    public function test_is_not_null_strategy_sign(): void
    {
        $sign = StrategyType::strategySign(StrategyType::IS_NOT_NULL);

        self::assertSame('IS NOT NULL', $sign);
    }

    public function test_wrong_strategy_sign(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        StrategyType::strategySign('wrong');
    }
}
