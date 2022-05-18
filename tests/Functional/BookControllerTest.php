<?php

namespace Bugloos\QueryFilterBundle\Tests\Functional;

use Exception;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\Book;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Story\BookUserCollectionStory;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClientTrait;
use Symfony\Component\HttpFoundation\Response;
use function Zenstruck\Foundry\repository;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @author Milad Ghofrani <milad.ghofrani@gmail.com>
 */
class BookControllerTest extends WebTestCase
{
    use Factories;
    use HttpClientTrait;
    use MailerAssertionsTrait;
    use ResetDatabase;
    use WebTestAssertionsTrait;

    /**
     * @throws Exception
     */
    public function test_no_filter_send_to_query_builder(): void
    {
        BookUserCollectionStory::load();
        static::ensureKernelShutdown();

        $client = static::createClient();

        $client->request('GET', '/api/filter/books');
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);
    }

    /**
     * @throws Exception
     */
    public function test_it_can_filter_native_field_with_no_relation(): void
    {
        BookUserCollectionStory::load();

        $firstBookTitle = repository(Book::class)->first()->getTitle();

        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'title' => $firstBookTitle,
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);

        foreach ($bookCollection as $book) {
            self::assertTrue(str_contains($firstBookTitle, $book['title']));
        }
    }

    /**
     * @throws Exception
     */
    public function test_it_can_sort_relation_field_with_one_level_relation(): void
    {
        BookUserCollectionStory::load();

        /** @var Book $firstBook */
        $firstBook = repository(Book::class)->first();

        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'country' => [$firstBook->getCountry()->getId()],
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);

        foreach ($bookCollection as $book) {
            self::assertSame($firstBook->getCountry()->getName(), $book['country']['name']);
        }
    }

    /**
     * @throws Exception
     */
    public function test_it_can_sort_relation_field_with_two_level_relation(): void
    {
        BookUserCollectionStory::load();

        /** @var Book $firstBook */
        $firstBook = repository(Book::class)->first();

        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'userAge' => $firstBook->getBookUsers()->first()->getUser()->getAge(),
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);

        foreach ($bookCollection as $book) {
            self::assertSame($firstBook->getBookUsers()->first()->getUser()->getAge(), $book['bookUsers'][0]['user']['age']);
        }
    }

    /**
     * @throws Exception
     */
    public function test_it_can_filter_multiple_fields(): void
    {
        BookUserCollectionStory::load();

        /** @var Book $firstBook */
        $firstBook = repository(Book::class)->first();

        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'title' => $firstBook->getTitle(),
                'status' => (1 === $firstBook->getStatus()) ? 0 : 1,
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertEmpty($bookCollection);
    }

    /**
     * @throws Exception
     */
    public function test_it_can_filter_multiple_fields_with_or(): void
    {
        BookUserCollectionStory::load();

        /** @var Book $firstBook */
        $firstBook = repository(Book::class)->first();
        $allDisableBooks = repository(Book::class)
            ->createQueryBuilder('b')
            ->andWhere('b.date > :date')
            ->setParameter('date', new \DateTime())
            ->andWhere('b.status = :status')
            ->setParameter('status', 0)
            ->getQuery()
            ->getResult()
        ;

        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'dateFrom' => 'now',
                'status' => (1 === $firstBook->getStatus()) ? 0 : 1,
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books/with-or', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);
        self::assertGreaterThan(count($allDisableBooks), count($bookCollection));
    }

    /**
     * @throws Exception
     */
    public function test_it_can_filter_date_fields_and_get_all_item_from_now(): void
    {
        BookUserCollectionStory::load();
        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'dateFrom' => 'now',
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);

        foreach ($bookCollection as $book) {
            self::assertGreaterThanOrEqual($book['date'], new \DateTime());
        }
    }

    /**
     * @throws Exception
     */
    public function test_it_can_filter_date_fields_and_get_all_item_from_now_used_multiple_methods(): void
    {
        BookUserCollectionStory::load();
        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'dateFrom' => 'now',
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books/multiple-methods', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);

        foreach ($bookCollection as $book) {
            self::assertGreaterThanOrEqual($book['date'], new \DateTime());
        }
    }

    /**
     * @throws Exception
     */
    public function test_it_can_filter_date_fields_and_get_all_item_to_now(): void
    {
        BookUserCollectionStory::load();
        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'dateTo' => 'now',
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);

        foreach ($bookCollection as $book) {
            self::assertLessThanOrEqual(new \DateTime(), $book['date']);
        }
    }

    /**
     * @throws Exception
     */
    public function test_it_can_filter_date_fields_and_get_all_item_from_and_to_value(): void
    {
        BookUserCollectionStory::load();
        $firstBookDate = repository(Book::class)->first()->getDate();

        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'dateFrom' => $firstBookDate->format('Y-m-d'),
                'dateTo' => $firstBookDate->format('Y-m-d'),
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);

        foreach ($bookCollection as $book) {
            self::assertGreaterThanOrEqual($book['date'], new \DateTime());
        }
    }

    /**
     * @throws Exception
     */
    public function test_it_can_filter_multiple_fields_and_one_field_value_is_empty(): void
    {
        BookUserCollectionStory::load();

        $firstBookTitle = repository(Book::class)->first()->getTitle();

        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'title' => $firstBookTitle,
                'price' => '',
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);

        foreach ($bookCollection as $book) {
            self::assertTrue(str_contains($firstBookTitle, $book['title']));
        }
    }

    /**
     * @throws Exception
     */
    public function test_it_is_working_if_send_empty_filter_data(): void
    {
        BookUserCollectionStory::load();
        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'title' => '',
                'price' => '',
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);
        $bookCollection = json_decode($client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertNotEmpty($bookCollection);
    }

    /**
     * @throws Exception
     */
    public function test_filter_native_wrong_field_with_no_relation(): void
    {
        BookUserCollectionStory::load();
        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'wrongField' => 'test',
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);

        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        self::assertTrue($client->getResponse()->isServerError());
        $this->throwException(new \InvalidArgumentException('You have selected the wrong field for filtering'));
    }

    /**
     * @throws Exception
     */
    public function test_filter_with_wrong_relation(): void
    {
        BookUserCollectionStory::load();
        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => [
                'wrongRelation' => 'test',
            ],
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);

        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        self::assertTrue($client->getResponse()->isServerError());
        $this->throwException(new \InvalidArgumentException('You have selected the wrong relation for filtering'));
    }

    /**
     * @throws Exception
     */
    public function test_send_non_array_parameters_to_filter(): void
    {
        BookUserCollectionStory::load();
        static::ensureKernelShutdown();

        $client = static::createClient();

        $queryParams = [
            'filters' => 'simple_string',
            'no_cache' => random_int(0, 999999),
        ];
        $client->request('GET', '/api/filter/books', $queryParams);

        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        self::assertTrue($client->getResponse()->isServerError());
        $this->throwException(new \InvalidArgumentException('Filter parameters should be an array type'));
    }

    public static function _resetSchema(): void
    {
    }
}
