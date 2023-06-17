<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\Service;

use Doctrine\Common\Annotations\Reader;

final class AttributeReader implements Reader
{
    public function getMethodAnnotations(\ReflectionMethod $method): array
    {
        $attributesRefs = $method->getAttributes();
        $attributes = [];
        foreach ($attributesRefs as $ref) {
            $attributes[] = $ref->newInstance();
        }

        return $attributes;
    }

    public function getClassAnnotations(\ReflectionClass $class): array
    {
        $attributesRefs = $class->getAttributes();
        $attributes = [];
        foreach ($attributesRefs as $ref) {
            $attribute = $ref->newInstance();
            $attributes[] = $attribute;
        }

        return $attributes;
    }

    public function getClassAnnotation(\ReflectionClass $class, $annotationName): ?object
    {
        $attributes = $class->getAttributes($annotationName, \ReflectionAttribute::IS_INSTANCEOF);
        if (isset($attributes[0])) {
            return $attributes[0]->newInstance();
        }

        return null;
    }

    public function getMethodAnnotation(\ReflectionMethod $method, $annotationName): ?object
    {
        $attributes = $method->getAttributes($annotationName, \ReflectionAttribute::IS_INSTANCEOF);
        if (isset($attributes[0])) {
            return $attributes[0]->newInstance();
        }

        return null;
    }

    public function getPropertyAnnotations(\ReflectionProperty $property): array
    {
        $attributesRefs = $property->getAttributes();
        $attributes = [];
        foreach ($attributesRefs as $ref) {
            $attributes[] = $ref->newInstance();
        }

        return $attributes;
    }

    public function getPropertyAnnotation(\ReflectionProperty $property, $annotationName): ?object
    {
        $attributes = $property->getAttributes($annotationName, \ReflectionAttribute::IS_INSTANCEOF);
        if (isset($attributes[0])) {
            return $attributes[0]->newInstance();
        }

        return null;
    }
}
