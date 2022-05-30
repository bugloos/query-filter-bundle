<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\FilterHandler\Contract;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ClassMetadata;
use Bugloos\QueryFilterBundle\Enum\ColumnType;
use Bugloos\QueryFilterBundle\TypeHandler\Contract\FilterValueInterface;
use Bugloos\QueryFilterBundle\TypeHandler\Factory\TypeFactory;
use ReflectionClass;
use ReflectionException;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
abstract class AbstractFilterHandler
{
    protected EntityManagerInterface $entityManager;
    protected string $fieldType;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    abstract public function filterParameter(
        $rootAlias,
        $rootClass,
        $relationsAndFieldName,
        $type
    ): string;

    abstract public function filterWhereClause(
        $rootAlias,
        $relationsAndFieldName,
        $filterParameter,
        $strategy,
        $value
    ): string;

    public function filterValue($value, $strategy)
    {
        $this->fieldType = (\is_array($value)) ? ColumnType::ARRAY : $this->fieldType;

        $typeHandler = TypeFactory::createTypeHandler($this->fieldType);
        if ($typeHandler instanceof FilterValueInterface) {
            return $typeHandler->filterValue($value, $strategy);
        }

        return $value;
    }

    protected function validateFieldNames($field, $class): void
    {
        if (!\in_array($field, $class->getFieldNames(), true)) {
            throw new \InvalidArgumentException(
                'You have selected the wrong field for filtering'
            );
        }
    }

    protected function validateRelationNames($relationProperty, $class): void
    {
        if (!\in_array($relationProperty, $class->getAssociationNames(), true)) {
            throw new \InvalidArgumentException(
                'You have selected the wrong relation for filtering'
            );
        }
    }

    protected function getRelationClass($class, $alias): ClassMetadata
    {
        $relationEntity = $class->getAssociationMapping($alias)['targetEntity'];

        return $this->entityManager->getClassMetadata($relationEntity);
    }

    protected function addRelationJoin($relationJoins, $alias, $property)
    {
        $relationJoins[sprintf('%s.%s', $alias, $property)] = $property;

        return $relationJoins;
    }

    protected function createParameterName($alias, $column): string
    {
        $random = mt_rand(1111,9999);
        return sprintf('%s_%s_%s', $alias, $column, $random);
    }

    /**
     * @param mixed $field
     * @param mixed $rootClass
     *
     * @throws ReflectionException
     */
    protected function getFieldTypeFromAnnotation($field, $rootClass)
    {
        $rootEntityRef = new ReflectionClass($rootClass->getName());
        $property = $rootEntityRef->getProperty($field);

        $annotationReader = new AnnotationReader();
        $propertyAnnotation = $annotationReader->getPropertyAnnotation($property, ORM\Column::class);

        return $propertyAnnotation->type;
    }
}
