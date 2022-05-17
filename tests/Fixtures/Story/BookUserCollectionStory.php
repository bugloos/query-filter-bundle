<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Story;

use Bugloos\QueryFilterBundle\Tests\Fixtures\Factory\BookUserFactory;
use Zenstruck\Foundry\Story;

final class BookUserCollectionStory extends Story
{
    public function build(): void
    {
        BookCollectionStory::load();
        UserCollectionStory::load();

        BookUserFactory::createMany(60);
    }
}
