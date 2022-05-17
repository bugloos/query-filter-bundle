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
class TwoLevelRelationHandler extends AbstractFilterHandler implements WithRelationInterface
{
    /**
     * @throws ReflectionException
     */
    public function filterParameter($rootAlias, $rootClass, $relationsAndFieldName, $type): string
    {
        [$relationAlias, $subRelationAlias, $subRelationField] = $relationsAndFieldName;

        $this->validateRelationNames($relationAlias, $rootClass);

        $relationClass = $this->getRelationClass($rootClass, $relationAlias);
        $this->validateRelationNames($subRelationAlias, $relationClass);

        $subRelationClass = $this->getRelationClass($relationClass, $subRelationAlias);
        $this->validateFieldNames($subRelationField, $subRelationClass);

        $this->fieldType = $this->getFieldTypeFromAnnotation($subRelationField, $subRelationClass);

        return $this->createParameterName($subRelationAlias, $subRelationField);
    }

    public function filterWhereClause(
        $rootAlias,
        $relationsAndFieldName,
        $filterParameter,
        $strategy
    ): string {
        [$relationAlias, $subRelationAlias, $subRelationField] = $relationsAndFieldName;

        return TypeFactory::createTypeHandler($this->fieldType)
            ->filterWhereClause($subRelationAlias, $subRelationField, $filterParameter, $strategy)
        ;
    }

    public function relationJoin($relationJoins, $rootAlias, $rootClass, $relationsAndFieldName): array
    {
        [$relationAlias, $subRelationAlias] = $relationsAndFieldName;

        $relationJoins = $this->addRelationJoin($relationJoins, $rootAlias, $relationAlias);

        return $this->addRelationJoin($relationJoins, $relationAlias, $subRelationAlias);
    }
}
