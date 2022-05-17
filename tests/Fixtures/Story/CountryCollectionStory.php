<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Story;

use Bugloos\QueryFilterBundle\Tests\Fixtures\Factory\CountryFactory;
use Zenstruck\Foundry\Story;

final class CountryCollectionStory extends Story
{
    public function build(): void
    {
        CountryFactory::createMany(31);
    }
}
