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
class ThreeLevelRelationHandler extends AbstractFilterHandler implements WithRelationInterface
{
    /**
     * @param mixed $rootAlias
     * @param mixed $rootClass
     * @param mixed $relationsAndFieldName
     * @param mixed $type
     *
     * @throws ReflectionException
     */
    public function filterParameter($rootAlias, $rootClass, $relationsAndFieldName, $type): string
    {
        [$relationAlias, $subRelationAlias, $thirdRelationAlias, $subRelationField] = $relationsAndFieldName;

        $this->validateRelationNames($relationAlias, $rootClass);

        $relationClass = $this->getRelationClass($rootClass, $relationAlias);
        $this->validateRelationNames($subRelationAlias, $relationClass);

        $subRelationClass = $this->getRelationClass($relationClass, $subRelationAlias);
        $this->validateRelationNames($thirdRelationAlias, $subRelationClass);

        $thirdRelationClass = $this->getRelationClass($subRelationClass, $thirdRelationAlias);
        $this->validateFieldNames($subRelationField, $thirdRelationClass);

        $this->fieldType = $this->getFieldTypeFromAnnotation($subRelationField, $thirdRelationClass);

        return $this->createParameterName($thirdRelationAlias, $subRelationField);
    }

    public function filterWhereClause(
        $rootAlias,
        $relationsAndFieldName,
        $filterParameter,
        $strategy,
        $value
    ): string {
        [$relationAlias, $subRelationAlias, $thirdRelationAlias, $subRelationField] = $relationsAndFieldName;

        return TypeFactory::createTypeHandler($this->fieldType)
            ->filterWhereClause($thirdRelationAlias, $subRelationField, $filterParameter, $strategy, $value)
        ;
    }

    public function relationJoin($relationJoins, $rootAlias, $rootClass, $relationsAndFieldName): array
    {
        [$relationAlias, $subRelationAlias, $thirdRelationAlias] = $relationsAndFieldName;

        $relationJoins = $this->addRelationJoin($relationJoins, $rootAlias, $relationAlias);

        $relationJoins = $this->addRelationJoin($relationJoins, $relationAlias, $subRelationAlias);

        return $this->addRelationJoin($relationJoins, $subRelationAlias, $thirdRelationAlias);
    }
}
