<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\Enum\Contract;

use InvalidArgumentException;
use ReflectionException;
use RuntimeException;

/**
 * Class Enum was developed by my dear friend Mojtaba Gheytasi <https://github.com/mojtaba-gheytasi>.
 *
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
abstract class Enum
{
    private static ?array $constCacheArray = null;

    public static function all(): array
    {
        return array_values(self::getConstants());
    }

    public static function getKeys(): array
    {
        return array_keys(self::getConstants());
    }

    public static function getKey($value): string
    {
        $constants = self::getConstants();

        if (!\in_array($value, $constants, true)) {
            self::createInvalidArgumentException('key');
        }

        return array_search($value, $constants, true);
    }

    private static function getConstants(): array
    {
        if (null === self::$constCacheArray) {
            self::$constCacheArray = [];
        }

        $calledClass = static::class;

        if (!\array_key_exists($calledClass, self::$constCacheArray)) {
            try {
                $reflect = new \ReflectionClass($calledClass);
            } catch (ReflectionException $e) {
                throw new RuntimeException();
            }
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return self::$constCacheArray[$calledClass];
    }

    /**
     * @param string $target
     */
    public static function createInvalidArgumentException(
        string $target
    ): void {
        throw new InvalidArgumentException(
            sprintf('Invalid argument, %s not exists in %s', $target, static::class)
        );
    }
}
