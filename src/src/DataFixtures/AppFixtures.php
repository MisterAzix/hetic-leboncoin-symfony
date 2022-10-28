<?php

namespace App\DataFixtures;

use App\Factory\AdFactory;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(20);
        TagFactory::createMany(35);
        AdFactory::createMany(20);
        QuestionFactory::createMany(20);
        AnswerFactory::createMany(20);
    }
}
