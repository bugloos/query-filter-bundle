<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Factory;

use Bugloos\QueryFilterBundle\Tests\Fixtures\Entity\User;
use Bugloos\QueryFilterBundle\Tests\Fixtures\Repository\UserRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static     User|Proxy createOne(array $attributes = [])
 * @method static     User[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static     User|Proxy find($criteria)
 * @method static     User|Proxy findOrCreate(array $attributes)
 * @method static     User|Proxy first(string $sortedField = 'id')
 * @method static     User|Proxy last(string $sortedField = 'id')
 * @method static     User|Proxy random(array $attributes = [])
 * @method static     User|Proxy randomOrCreate(array $attributes = [])
 * @method static     User[]|Proxy[] all()
 * @method static     User[]|Proxy[] findBy(array $attributes)
 * @method static     User[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static     User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static     UserRepository|RepositoryProxy repository()
 * @method Proxy|User create($attributes = [])
 */
final class UserFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->firstName(),
            'surname' => self::faker()->lastName(),
            'age' => self::faker()->numberBetween(15, 80),
            'status' => self::faker()->randomElement([0, 1]),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(User $commercialType) {})
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
