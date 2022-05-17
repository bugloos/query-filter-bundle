<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\Enum;

use Bugloos\QueryFilterBundle\Enum\Contract\Enum;

final class ColumnType extends Enum
{
    // String Types
    public const STRING = 'string';
    public const TEXT = 'text';

    // Boolean Types
    public const BOOLEAN = 'boolean';

    // Integer Types
    public const INTEGER = 'integer';
    public const SMALL_INT = 'smallint';
    public const BIG_INT = 'bigint';
    public const FLOAT = 'float';
    public const DECIMAL = 'decimal';

    // Array Types
    public const ARRAY = 'array';

    // Date-Time Types
    public const DATE = 'date';
    public const TIME = 'time';
    public const DATE_TIME = 'datetime';

    // Nullable Types
    public const NULLABLE = 'nullable';
}
