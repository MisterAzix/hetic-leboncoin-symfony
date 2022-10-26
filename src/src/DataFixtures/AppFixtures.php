<?php

namespace App\DataFixtures;

use App\Factory\AdFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        TagFactory::createMany(25);
        AdFactory::createMany(10);
    }
}
