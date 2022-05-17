<?php

namespace Bugloos\QueryFilterBundle\Tests\Unit\FilterHandler\Factory;

use Bugloos\QueryFilterBundle\FilterHandler\Factory\FilterFactory;
use Bugloos\QueryFilterBundle\FilterHandler\NoRelationHandler;
use Bugloos\QueryFilterBundle\FilterHandler\OneLevelRelationHandler;
use Bugloos\QueryFilterBundle\FilterHandler\TwoLevelRelationHandler;
use PHPUnit\Framework\TestCase;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class FilterFactoryTest extends TestCase
{
    public function test_filter_handler_no_relation(): void
    {
        $entityManager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->setMethods(['getRepository'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $filterFactory = new FilterFactory($entityManager);

        $relationHandler = $filterFactory->createFilterHandler(['title']);

        self::assertInstanceOf(NoRelationHandler::class, $relationHandler);
    }

    public function test_filter_handler_with_one_level_relation(): void
    {
        $entityManager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->setMethods(['getRepository'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $filterFactory = new FilterFactory($entityManager);

        $relationHandler = $filterFactory->createFilterHandler(['country', 'name']);

        self::assertInstanceOf(OneLevelRelationHandler::class, $relationHandler);
    }

    public function test_filter_handler_with_two_level_relation(): void
    {
        $entityManager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->setMethods(['getRepository'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $filterFactory = new FilterFactory($entityManager);

        $relationHandler = $filterFactory->createFilterHandler(['bookUsers', 'user', 'age']);

        self::assertInstanceOf(TwoLevelRelationHandler::class, $relationHandler);
    }

    public function test_filter_handler_with_an_exception_when_need_relation_more_than_two_level(): void
    {
        $entityManager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->setMethods(['getRepository'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $filterFactory = new FilterFactory($entityManager);

        $this->expectException(\RuntimeException::class);

        $filterFactory->createFilterHandler(['bookUsers', 'user', 'country', 'name']);
    }
}
