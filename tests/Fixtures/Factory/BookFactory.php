<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Factory;

use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\Book;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Repository\BookRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static     Book|Proxy createOne(array $attributes = [])
 * @method static     Book[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static     Book|Proxy find($criteria)
 * @method static     Book|Proxy findOrCreate(array $attributes)
 * @method static     Book|Proxy first(string $sortedField = 'id')
 * @method static     Book|Proxy last(string $sortedField = 'id')
 * @method static     Book|Proxy random(array $attributes = [])
 * @method static     Book|Proxy randomOrCreate(array $attributes = [])
 * @method static     Book[]|Proxy[] all()
 * @method static     Book[]|Proxy[] findBy(array $attributes)
 * @method static     Book[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static     Book[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static     BookRepository|RepositoryProxy repository()
 * @method Book|Proxy create($attributes = [])
 */
final class BookFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->sentence,
            'date' => self::faker()->dateTimeBetween('-2 month', '+2 month'),
            'description' => self::faker()->paragraph,
            'price' => self::faker()->randomFloat(2, 1000, 10000),
            'status' => self::faker()->randomElement([0, 1]),
            'country' => CountryFactory::random(),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Book $commercialType) {})
        ;
    }

    protected static function getClass(): string
    {
        return Book::class;
    }
}
