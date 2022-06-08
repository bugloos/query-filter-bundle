<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\Service;

use Bugloos\QueryFilterBundle\Enum\ColumnType;
use Closure;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use JsonException;
use Bugloos\QueryFilterBundle\FilterHandler\Contract\WithRelationInterface;
use Bugloos\QueryFilterBundle\FilterHandler\Factory\FilterFactory;
use Bugloos\QueryFilterBundle\Traits\QueryFilterTrait;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class QueryFilter
{
    use QueryFilterTrait;

    private const DEFAULT_CACHE_TIME = 3600;

    private const SEPARATOR = '.';

    private EntityManagerInterface $entityManager;

    private FilterFactory $filterFactory;

    private CacheInterface $cache;

    private string $rootAlias;

    private string $rootEntity;

    private ClassMetadata $rootClass;

    private QueryBuilder $query;

    private string $cacheKey;

    private array $filters = [];

    private array $mapper = [];

    private array $strategies = [];

    private array $types = [];

    private array $constants = [];

    private ?int $cacheTime = null;

    private bool $withOr = false;

    private int $defaultCacheTime;

    private string $separator;

    public function __construct(
        EntityManagerInterface $entityManager,
        CacheInterface $cache,
        FilterFactory $filterFactory,
        $defaultCacheTime = self::DEFAULT_CACHE_TIME,
        $separator = self::SEPARATOR
    ) {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
        $this->filterFactory = $filterFactory;
        $this->defaultCacheTime = $defaultCacheTime;
        $this->separator = $separator;
    }

    public function for(QueryBuilder $query): self
    {
        $this->initializeRootQueryConfig($query);

        return $this;
    }

    /**
     * @param $filters
     *
     * @return self
     *
     * @throws JsonException
     */
    public function parameters($filters): self
    {
        if (empty($filters)) {
            return $this;
        }

        if (!\is_array($filters)) {
            throw new \InvalidArgumentException(
                'Filter parameters should be an array type'
            );
        }

        // Remove empty value from array
        $this->filters = array_filter($filters, static function ($var) {
            return null !== $var && '' !== $var;
        });

        // Create cache key by request
        $this->createCacheKey($this->filters);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function filter(): QueryBuilder
    {
        // Early return if array is empty
        if (empty($this->filters)) {
            return $this->query;
        }
        // Calculate and cache fields.
        [$filterParameters, $filterWhereClauses, $relationJoins] = $this->cache->get(
            $this->cacheKey,
            $this->addWhereClauses()
        );

        $this->applyRelationJoinToQuery($relationJoins);

        $this->applyWhereClausesToQuery($filterWhereClauses);

        $this->applyParametersToQuery($filterParameters);

        return $this->query;
    }

    private function applyWhereClausesToQuery($filterWhereClauses): void
    {
        if (!empty($filterWhereClauses)) {
            if ($this->withOr) {
                $this->query->andWhere($this->query->expr()->orX()->addMultiple($filterWhereClauses));
            } else {
                $this->query->andWhere($this->query->expr()->andx()->addMultiple($filterWhereClauses));
            }
        }
    }

    private function applyParametersToQuery($filterParameters): void
    {
        foreach ($filterParameters as $parameterName => $parameterValue) {
            $this->query->setParameter($parameterName, $parameterValue);
        }
    }

    private function applyRelationJoinToQuery($relationJoins): void
    {
        // Remove exist joined from a list
        $filteredJoins = array_diff($relationJoins, $this->query->getAllAliases());

        // Add a left join to query which does not exist in the query
        if (!empty($filteredJoins)) {
            foreach ($filteredJoins as $property => $column) {
                $this->query->addSelect($column);
                $this->query->leftJoin($property, $column);
            }
        }
    }

    /**
     * @param $array
     *
     * @throws JsonException
     */
    private function createCacheKey($array): void
    {
        $this->cacheKey = md5($this->rootEntity.json_encode($array, \JSON_THROW_ON_ERROR));
    }

    /**
     * @author Milad Ghofrani <milad.ghofrani@gmail.com>
     */
    private function addWhereClauses(): Closure
    {
        return function (CacheItemInterface $item) {
            $item->expiresAfter($this->cacheTime ?: $this->defaultCacheTime);

            $filterParameters = [];
            $filterWhereClauses = [];
            $relationJoins = [];

            foreach ($this->filters as $parameter => $value) {
                // Check user set a strategy for this $parameter
                $strategy = $this->strategies[$parameter] ?? null;

                // Check user set a type for this $parameter
                $type = $this->types[$parameter] ?? null;

                // Check $parameter exists in mapper
                $checkedParameter = (\array_key_exists($parameter, $this->mapper))
                    ? $this->mapper[$parameter] : $parameter;

                $relationsAndFieldName = explode($this->separator, $checkedParameter);

                $filteringHandler = $this->filterFactory->createFilterHandler($relationsAndFieldName);

                $filterParameter = $filteringHandler->filterParameter(
                    $this->rootAlias,
                    $this->rootClass,
                    $relationsAndFieldName,
                    $type
                );

                if($type !== ColumnType::NULLABLE){
                    $filterParameters[$filterParameter] = $filteringHandler->filterValue($value, $strategy);
                }

                $whereClause = $filteringHandler->filterWhereClause(
                    $this->rootAlias,
                    $relationsAndFieldName,
                    $filterParameter,
                    $strategy,
                    $value
                );

                if(\array_key_exists($parameter, $this->constants) && empty($this->constants[$parameter]) === false){
                    $whereClause = "( " . $whereClause;
                    $whereClause .= " AND " . $this->constants[$parameter];
                    $whereClause .= " )";
                }

                $filterWhereClauses[] = $whereClause;

                if ($filteringHandler instanceof WithRelationInterface) {
                    $relationJoins = $filteringHandler->relationJoin(
                        $relationJoins,
                        $this->rootAlias,
                        $this->rootClass,
                        $relationsAndFieldName
                    );
                }
            }

            return [$filterParameters, $filterWhereClauses, $relationJoins];
        };
    }

    private function initializeRootQueryConfig($query): void
    {
        $rootEntities = $query->getRootEntities();
        $rootAliasArray = $query->getRootAliases();

        if (!isset($rootEntities[0], $rootAliasArray[0])) {
            throw new \InvalidArgumentException('Root Alias not defined correctly.');
        }

        $this->query = $query;
        $this->rootAlias = $rootAliasArray[0];
        $this->rootEntity = $rootEntities[0];
        $this->rootClass = $this->entityManager->getClassMetadata($this->rootEntity);
    }
}
