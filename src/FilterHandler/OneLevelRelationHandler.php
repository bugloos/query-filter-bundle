<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\FilterHandler;

use Bugloos\QueryFilterBundle\FilterHandler\Contract\AbstractFilterHandler;
use Bugloos\QueryFilterBundle\FilterHandler\Contract\WithRelationInterface;
use Bugloos\QueryFilterBundle\TypeHandler\Factory\TypeFactory;
use ReflectionException;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class OneLevelRelationHandler extends AbstractFilterHandler implements WithRelationInterface
{
    /**
     * @throws ReflectionException
     */
    public function filterParameter($rootAlias, $rootClass, $relationsAndFieldName, $type): string
    {
        [$relationAlias, $relationField] = $relationsAndFieldName;

        $this->validateRelationNames($relationAlias, $rootClass);

        $relationClass = $this->getRelationClass($rootClass, $relationAlias);
        $this->validateFieldNames($relationField, $relationClass);

        $this->fieldType = $this->getFieldTypeFromAnnotation($relationField, $relationClass);

        return $this->createParameterName($relationAlias, $relationField);
    }

    public function filterWhereClause(
        $rootAlias,
        $relationsAndFieldName,
        $filterParameter,
        $strategy,
        $value
    ): string {
        [$relationAlias, $relationField] = $relationsAndFieldName;

        return TypeFactory::createTypeHandler($this->fieldType)
            ->filterWhereClause($relationAlias, $relationField, $filterParameter, $strategy, $value)
        ;
    }

    public function relationJoin($relationJoins, $rootAlias, $rootClass, $relationsAndFieldName): array
    {
        [$relationAlias] = $relationsAndFieldName;

        return $this->addRelationJoin($relationJoins, $rootAlias, $relationAlias);
    }
}
