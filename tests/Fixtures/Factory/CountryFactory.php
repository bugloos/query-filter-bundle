<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Factory;

use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\Country;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Repository\CountryRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static        Country|Proxy createOne(array $attributes = [])
 * @method static        Country[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static        Country|Proxy find($criteria)
 * @method static        Country|Proxy findOrCreate(array $attributes)
 * @method static        Country|Proxy first(string $sortedField = 'id')
 * @method static        Country|Proxy last(string $sortedField = 'id')
 * @method static        Country|Proxy random(array $attributes = [])
 * @method static        Country|Proxy randomOrCreate(array $attributes = [])
 * @method static        Country[]|Proxy[] all()
 * @method static        Country[]|Proxy[] findBy(array $attributes)
 * @method static        Country[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static        Country[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static        CountryRepository|RepositoryProxy repository()
 * @method Country|Proxy create($attributes = [])
 */
final class CountryFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->country(),
            'status' => self::faker()->randomElement([0, 1]),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Country $commercialType) {})
        ;
    }

    protected static function getClass(): string
    {
        return Country::class;
    }
}
