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
class FourLevelRelationHandler extends AbstractFilterHandler implements WithRelationInterface
{
    /**
     * @throws ReflectionException
     */
    public function filterParameter($rootAlias, $rootClass, $relationsAndFieldName, $type): string
    {
        [$relationAlias, $subRelationAlias, $thirdRelationAlias, $fourthRelationAlias, $subRelationField] = $relationsAndFieldName;

        $this->validateRelationNames($relationAlias, $rootClass);

        $relationClass = $this->getRelationClass($rootClass, $relationAlias);
        $this->validateRelationNames($subRelationAlias, $relationClass);

        $subRelationClass = $this->getRelationClass($relationClass, $subRelationAlias);
        $this->validateRelationNames($thirdRelationAlias, $subRelationClass);

        $thirdRelationClass = $this->getRelationClass($subRelationClass, $thirdRelationAlias);

        $this->validateRelationNames($fourthRelationAlias, $thirdRelationClass);

        $fourthRelationClass = $this->getRelationClass($thirdRelationClass, $fourthRelationAlias);
        $this->validateFieldNames($subRelationField, $thirdRelationClass);

        $this->fieldType = $this->getFieldTypeFromAnnotation($subRelationField, $fourthRelationClass);

        return $this->createParameterName($fourthRelationAlias, $subRelationField);
    }

    public function filterWhereClause(
        $rootAlias,
        $relationsAndFieldName,
        $filterParameter,
        $strategy,
        $value
    ): string {
        [$relationAlias, $subRelationAlias, $thirdRelationAlias, $fourthRelationAlias, $subRelationField] = $relationsAndFieldName;

        return TypeFactory::createTypeHandler($this->fieldType)
            ->filterWhereClause($fourthRelationAlias, $subRelationField, $filterParameter, $strategy, $value);
    }

    public function relationJoin($relationJoins, $rootAlias, $rootClass, $relationsAndFieldName): array
    {
        [$relationAlias, $subRelationAlias, $thirdRelationAlias, $fourthRelationAlias] = $relationsAndFieldName;

        $relationJoins = $this->addRelationJoin($relationJoins, $rootAlias, $relationAlias);

        $relationJoins = $this->addRelationJoin($relationJoins, $relationAlias, $subRelationAlias);

        $relationJoins = $this->addRelationJoin($relationJoins, $subRelationAlias, $thirdRelationAlias);

        return $this->addRelationJoin($relationJoins, $thirdRelationAlias, $fourthRelationAlias);
    }
}
