<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\BookUser;

/**
 * @method null|BookUser find($id, $lockMode = null, $lockVersion = null)
 * @method null|BookUser findOneBy(array $criteria, array $orderBy = null)
 * @method BookUser[]    findAll()
 * @method BookUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookUser::class);
    }
}
