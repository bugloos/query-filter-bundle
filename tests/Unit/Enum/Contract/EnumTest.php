<?php

namespace Bugloos\QueryFilterBundle\Tests\Unit\Enum\Contract;

use Bugloos\QueryFilterBundle\Enum\StrategyType;
use PHPUnit\Framework\TestCase;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class EnumTest extends TestCase
{
    public function test_get_all(): void
    {
        $values = [
            'exact',
            'partial',
            'start',
            'end',
            'word_start',
            'array',
            'after',
            'before',
            'is_null',
            'is_not_null',
        ];

        $getValues = StrategyType::all();

        self::assertSame($values, $getValues);
    }

    public function test_get_keys(): void
    {
        $keys = [
            'EXACT',
            'PARTIAL',
            'START',
            'END',
            'WORD_START',
            'ARRAY',
            'AFTER',
            'BEFORE',
            'IS_NULL',
            'IS_NOT_NULL',
        ];

        $getKeys = StrategyType::getKeys();

        self::assertSame($keys, $getKeys);
    }

    public function test_get_key(): void
    {
        $getKey = StrategyType::getKey('exact');

        self::assertSame('EXACT', $getKey);
    }

    public function test_get_key_with_wrong_key(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        StrategyType::getKey('wrong');
    }
}
