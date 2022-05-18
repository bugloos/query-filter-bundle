<?php

declare(strict_types=1);

namespace Bugloos\QueryFilterBundle\FilterHandler\Factory;

use Bugloos\QueryFilterBundle\FilterHandler\Contract\AbstractFilterHandler;
use Bugloos\QueryFilterBundle\FilterHandler\NoRelationHandler;
use Bugloos\QueryFilterBundle\FilterHandler\OneLevelRelationHandler;
use Bugloos\QueryFilterBundle\FilterHandler\TwoLevelRelationHandler;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class FilterFactory
{
    private const NO_RELATION = 1;
    private const ONE_LEVEL_RELATION = 2;
    private const TWO_LEVEL_RELATION = 3;

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $relationsAndFieldName
     *
     * @return AbstractFilterHandler
     */
    public function createFilterHandler(array $relationsAndFieldName): AbstractFilterHandler
    {
        return match (\count($relationsAndFieldName)) {
            self::NO_RELATION => new NoRelationHandler($this->entityManager),
            self::ONE_LEVEL_RELATION => new OneLevelRelationHandler($this->entityManager),
            self::TWO_LEVEL_RELATION => new TwoLevelRelationHandler($this->entityManager),
            default => throw new \RuntimeException(
                'This Bundle just support maximum two-level deep relation'
            ),
        };
    }
}
