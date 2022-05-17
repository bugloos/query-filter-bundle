<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Story;

use Bugloos\QueryFilterBundle\Tests\Fixtures\Factory\BookFactory;
use Zenstruck\Foundry\Story;

final class BookCollectionStory extends Story
{
    public function build(): void
    {
        CountryCollectionStory::load();

        BookFactory::createMany(31);
    }
}
