<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\TypeHandler\Factory;

use Bugloos\QueryFilterBundle\Enum\ColumnType;
use Bugloos\QueryFilterBundle\TypeHandler\ArrayHandler;
use Bugloos\QueryFilterBundle\TypeHandler\BooleanHandler;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\AbstractTypeHandler;
use Bugloos\QueryFilterBundle\TypeHandler\DateHandler;
use Bugloos\QueryFilterBundle\TypeHandler\IntegerHandler;
use Bugloos\QueryFilterBundle\TypeHandler\NullableHandler;
use Bugloos\QueryFilterBundle\TypeHandler\StringHandler;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class TypeFactory
{
    public static function createTypeHandler(string $type): AbstractTypeHandler
    {
        $constants = ColumnType::all();

        if (!\in_array($type, $constants, true)) {
            ColumnType::createInvalidArgumentException($type);
        }

        $mapperList = [
            ColumnType::STRING => new StringHandler(),
            ColumnType::TEXT => new StringHandler(),
            ColumnType::BOOLEAN => new BooleanHandler(),
            ColumnType::INTEGER => new IntegerHandler(),
            ColumnType::SMALL_INT => new IntegerHandler(),
            ColumnType::BIG_INT => new IntegerHandler(),
            ColumnType::FLOAT => new IntegerHandler(),
            ColumnType::DECIMAL => new IntegerHandler(),
            ColumnType::ARRAY => new ArrayHandler(),
            ColumnType::DATE => new DateHandler(),
            ColumnType::TIME => new DateHandler(),
            ColumnType::DATE_TIME => new DateHandler(),
            ColumnType::NULLABLE => new NullableHandler(),
        ];

        return $mapperList[$type];
    }
}
