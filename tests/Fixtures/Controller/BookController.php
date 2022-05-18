<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Bugloos\QueryFilterBundle\Enum\StrategyType;
use Bugloos\QueryFilterBundle\Service\QueryFilter;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\Book;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class BookController extends AbstractController
{
    private QueryFilter $queryFilter;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        QueryFilter $queryFilter
    ) {
        $this->entityManager = $entityManager;
        $this->queryFilter = $queryFilter;
    }

    /**
     * @Route("/api/filter/books", methods={"GET"})
     *
     * @param Request $request
     *
     * @throws Exception
     * @throws InvalidArgumentException
     *
     * @return JsonResponse
     */
    public function sortBooks(Request $request): JsonResponse
    {
        $queryBuilder = $this->entityManager->getRepository(Book::class)->createQueryBuilder('b');

        $queryBuilder = $this->queryFilter->for($queryBuilder)
            ->parameters($request->get('filters'))
            ->addMapper('dateFrom', 'date')
            ->addStrategy('dateFrom', StrategyType::AFTER_EXACT)
            ->addMapper('dateTo', 'date')
            ->addStrategy('dateTo', StrategyType::BEFORE_EXACT)
            ->addMapper('country', 'country.id')
            ->addMapper('userAge', 'bookUsers.user.age')
            ->addMapper('wrongRelation', 'wrong.name')
            ->filter()
        ;

        return new JsonResponse($queryBuilder->getQuery()->getArrayResult());
    }

    /**
     * @Route("/api/filter/books/multiple-methods", methods={"GET"})
     *
     * @param Request $request
     *
     * @throws Exception
     * @throws InvalidArgumentException
     *
     * @return JsonResponse
     */
    public function sortBooksUsesMultipleMethods(Request $request): JsonResponse
    {
        $queryBuilder = $this->entityManager->getRepository(Book::class)->createQueryBuilder('b');

        $queryBuilder = $this->queryFilter->for($queryBuilder)
            ->parameters($request->get('filters'))
            ->mappers([
                'dateFrom' => 'date',
                'dateTo' => 'date',
                'country' => 'country.id',
                'userAge' => 'bookUsers.user.age',
            ])
            ->strategies([
                'dateFrom' => StrategyType::AFTER,
                'dateTo' => StrategyType::BEFORE,
            ])
            ->filter()
        ;

        return new JsonResponse($queryBuilder->getQuery()->getArrayResult());
    }

    /**
     * @Route("/api/filter/books/with-or", methods={"GET"})
     *
     * @param Request $request
     *
     * @throws Exception
     * @throws InvalidArgumentException
     *
     * @return JsonResponse
     */
    public function sortBooksWithOr(Request $request): JsonResponse
    {
        $queryBuilder = $this->entityManager->getRepository(Book::class)->createQueryBuilder('b');

        $queryBuilder = $this->queryFilter->for($queryBuilder)
            ->parameters($request->get('filters'))
            ->addMapper('dateFrom', 'date')
            ->addStrategy('dateFrom', StrategyType::AFTER)
            ->withOr(true)
            ->filter()
        ;

        return new JsonResponse($queryBuilder->getQuery()->getArrayResult());
    }
}
