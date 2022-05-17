<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\Country;

/**
 * @method null|Country find($id, $lockMode = null, $lockVersion = null)
 * @method null|Country findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }
}
