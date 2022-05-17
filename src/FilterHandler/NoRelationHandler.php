<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\FilterHandler;

use Bugloos\QueryFilterBundle\FilterHandler\Contract\AbstractFilterHandler;
use Bugloos\QueryFilterBundle\TypeHandler\Factory\TypeFactory;
use ReflectionException;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class NoRelationHandler extends AbstractFilterHandler
{
    /**
     * @throws ReflectionException
     */
    public function filterParameter($rootAlias, $rootClass, $relationsAndFieldName, $type): string
    {
        $alias = $rootAlias;
        $field = $relationsAndFieldName[0];

        $this->validateFieldNames($field, $rootClass);

        $this->fieldType = $type ?: $this->getFieldTypeFromAnnotation($field, $rootClass);

        return $this->createParameterName($alias, $field);
    }

    public function filterWhereClause(
        $rootAlias,
        $relationsAndFieldName,
        $filterParameter,
        $strategy
    ): string {
        $alias = $rootAlias;
        $field = $relationsAndFieldName[0];

        return TypeFactory::createTypeHandler($this->fieldType)
            ->filterWhereClause($alias, $field, $filterParameter, $strategy)
        ;
    }
}
