<?php

namespace Bugloos\QueryFilterBundle\Traits;

use Bugloos\QueryFilterBundle\Enum\ColumnType;
use Bugloos\QueryFilterBundle\Enum\StrategyType;

trait QueryFilterTrait
{
    public function strategies(array $strategies): self
    {
        if (empty($strategies)) {
            return $this;
        }

        foreach ($strategies as $parameter => $strategy) {
            $this->addStrategy($parameter, $strategy);
        }

        return $this;
    }

    public function addStrategy(string $parameter, string $strategy): self
    {
        if (empty($parameter) || empty($strategy)) {
            return $this;
        }

        if (!\in_array($strategy, StrategyType::all(), true)) {
            StrategyType::createInvalidArgumentException($strategy);
        }

        $this->strategies[$parameter] = $strategy;

        return $this;
    }

    public function types(array $types): self
    {
        if (empty($types)) {
            return $this;
        }

        foreach ($types as $parameter => $type) {
            $this->addType($parameter, $type);
        }

        return $this;
    }

    public function addType(string $parameter, string $type): self
    {
        if (empty($parameter) || empty($type)) {
            return $this;
        }

        if (!\in_array($type, ColumnType::all(), true)) {
            StrategyType::createInvalidArgumentException($type);
        }

        $this->types[$parameter] = $type;

        return $this;
    }

    public function mappers(array $mappers): self
    {
        if (empty($mappers)) {
            return $this;
        }

        foreach ($mappers as $parameter => $mapper) {
            $this->addMapper($parameter, $mapper);
        }

        return $this;
    }

    public function addMapper(string $parameter, string $mapper): self
    {
        if (empty($parameter) || empty($mapper)) {
            return $this;
        }

        $this->mapper[$parameter] = $mapper;

        return $this;
    }

    public function constants(array $constants): self
    {
        if (empty($constants)) {
            return $this;
        }

        foreach ($constants as $parameter => $condition) {
            $this->addConstant($parameter, $condition);
        }

        return $this;
    }

    public function addConstant(string $parameter, string $condition): self
    {
        if (empty($parameter) || empty($condition)) {
            return $this;
        }

        $this->constants[$parameter] = $condition;

        return $this;
    }

    public function cacheTime(int $cacheTime): self
    {
        $this->cacheTime = $cacheTime;

        return $this;
    }

    public function withOr(bool $withOr): self
    {
        $this->withOr = $withOr;

        return $this;
    }
}
