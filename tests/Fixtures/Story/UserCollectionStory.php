<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Story;

use Bugloos\QueryFilterBundle\Tests\Fixtures\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserCollectionStory extends Story
{
    public function build(): void
    {
        UserFactory::createMany(31);
    }
}
