<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\Enum;

use Bugloos\QueryFilterBundle\Enum\Contract\Enum;

final class StrategyType extends Enum
{
    public const EXACT = 'exact';
    public const PARTIAL = 'partial';
    public const START = 'start';
    public const END = 'end';
    public const WORD_START = 'word_start';
    public const ARRAY = 'array';
    public const AFTER = 'after';
    public const AFTER_EXACT = 'after_exact';
    public const BEFORE = 'before';
    public const BEFORE_EXACT = 'before_exact';
    public const IS_NULL = 'is_null';
    public const IS_NOT_NULL = 'is_not_null';

    public static function strategyValue($strategy, $value): string
    {
        $constants = self::all();

        if (!\in_array($strategy, $constants, true)) {
            self::createInvalidArgumentException($strategy);
        }

        $mapperList = [
            self::EXACT => $value,
            self::PARTIAL => '%'.$value.'%',
            self::START => $value.'%',
            self::END => '%'.$value,
            self::WORD_START => '% '.$value.'%',
            self::ARRAY => $value,
            self::AFTER => $value,
            self::AFTER_EXACT => $value,
            self::BEFORE => $value,
            self::BEFORE_EXACT => $value,
            self::IS_NULL => $value,
            self::IS_NOT_NULL => $value,
        ];

        return $mapperList[$strategy];
    }

    public static function strategySign($strategy): string
    {
        $constants = self::all();

        if (!\in_array($strategy, $constants, true)) {
            self::createInvalidArgumentException($strategy);
        }

        $mapperList = [
            self::EXACT => '=',
            self::PARTIAL => 'LIKE',
            self::START => 'LIKE',
            self::END => 'LIKE',
            self::WORD_START => 'LIKE',
            self::ARRAY => 'IN',
            self::AFTER => '>',
            self::AFTER_EXACT => '>=',
            self::BEFORE => '<',
            self::BEFORE_EXACT => '<=',
            self::IS_NULL => 'IS NULL',
            self::IS_NOT_NULL => 'IS NOT NULL',
        ];

        return $mapperList[$strategy];
    }
}
