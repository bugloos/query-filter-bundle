<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\User;

/**
 * @method null|User find($id, $lockMode = null, $lockVersion = null)
 * @method null|User findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
