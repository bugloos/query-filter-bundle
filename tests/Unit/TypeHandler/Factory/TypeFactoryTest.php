<?php

namespace Bugloos\QueryFilterBundle\Tests\Unit\TypeHandler\Factory;

use Bugloos\QueryFilterBundle\Enum\ColumnType;
use Bugloos\QueryFilterBundle\TypeHandler\ArrayHandler;
use Bugloos\QueryFilterBundle\TypeHandler\BooleanHandler;
use Bugloos\QueryFilterBundle\TypeHandler\DateHandler;
use Bugloos\QueryFilterBundle\TypeHandler\Factory\TypeFactory;
use Bugloos\QueryFilterBundle\TypeHandler\IntegerHandler;
use Bugloos\QueryFilterBundle\TypeHandler\NullableHandler;
use Bugloos\QueryFilterBundle\TypeHandler\StringHandler;
use PHPUnit\Framework\TestCase;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class TypeFactoryTest extends TestCase
{
    public function test_type_handler_is_string(): void
    {
        self::assertInstanceOf(StringHandler::class, TypeFactory::createTypeHandler(ColumnType::STRING));
    }

    public function test_type_handler_is_text(): void
    {
        self::assertInstanceOf(StringHandler::class, TypeFactory::createTypeHandler(ColumnType::TEXT));
    }

    public function test_type_handler_is_boolean(): void
    {
        self::assertInstanceOf(BooleanHandler::class, TypeFactory::createTypeHandler(ColumnType::BOOLEAN));
    }

    public function test_type_handler_is_integer(): void
    {
        self::assertInstanceOf(IntegerHandler::class, TypeFactory::createTypeHandler(ColumnType::INTEGER));
    }

    public function test_type_handler_is_small_int(): void
    {
        self::assertInstanceOf(IntegerHandler::class, TypeFactory::createTypeHandler(ColumnType::SMALL_INT));
    }

    public function test_type_handler_is_big_int(): void
    {
        self::assertInstanceOf(IntegerHandler::class, TypeFactory::createTypeHandler(ColumnType::BIG_INT));
    }

    public function test_type_handler_is_float(): void
    {
        self::assertInstanceOf(IntegerHandler::class, TypeFactory::createTypeHandler(ColumnType::FLOAT));
    }

    public function test_type_handler_is_decimal(): void
    {
        self::assertInstanceOf(IntegerHandler::class, TypeFactory::createTypeHandler(ColumnType::DECIMAL));
    }

    public function test_type_handler_is_array(): void
    {
        self::assertInstanceOf(ArrayHandler::class, TypeFactory::createTypeHandler(ColumnType::ARRAY));
    }

    public function test_type_handler_is_date(): void
    {
        self::assertInstanceOf(DateHandler::class, TypeFactory::createTypeHandler(ColumnType::DATE));
    }

    public function test_type_handler_is_time(): void
    {
        self::assertInstanceOf(DateHandler::class, TypeFactory::createTypeHandler(ColumnType::TIME));
    }

    public function test_type_handler_is_date_time(): void
    {
        self::assertInstanceOf(DateHandler::class, TypeFactory::createTypeHandler(ColumnType::DATE_TIME));
    }

    public function test_type_handler_is_nullable(): void
    {
        self::assertInstanceOf(NullableHandler::class, TypeFactory::createTypeHandler(ColumnType::NULLABLE));
    }

    public function test_type_handler_with_wrong_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        TypeFactory::createTypeHandler('wrong_type');
    }
}
