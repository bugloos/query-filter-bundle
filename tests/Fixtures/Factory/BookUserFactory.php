<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Factory;

use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\BookUser;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Repository\BookUserRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static         BookUser|Proxy createOne(array $attributes = [])
 * @method static         BookUser[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static         BookUser|Proxy find($criteria)
 * @method static         BookUser|Proxy findOrCreate(array $attributes)
 * @method static         BookUser|Proxy first(string $sortedField = 'id')
 * @method static         BookUser|Proxy last(string $sortedField = 'id')
 * @method static         BookUser|Proxy random(array $attributes = [])
 * @method static         BookUser|Proxy randomOrCreate(array $attributes = [])
 * @method static         BookUser[]|Proxy[] all()
 * @method static         BookUser[]|Proxy[] findBy(array $attributes)
 * @method static         BookUser[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static         BookUser[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static         BookUserRepository|RepositoryProxy repository()
 * @method BookUser|Proxy create($attributes = [])
 */
final class BookUserFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'book' => BookFactory::random(),
            'user' => UserFactory::random(),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(BookUser $commercialType) {})
        ;
    }

    protected static function getClass(): string
    {
        return BookUser::class;
    }
}
