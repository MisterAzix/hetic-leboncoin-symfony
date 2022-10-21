<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    //    Méthode classique
    //    public function load(ObjectManager $manager): void
    //    {
    //        // $product = new Product();
    //        // $manager->persist($product);
    //
    //        //Créer une question  avec des données fictives
    //        $question = new Question();
    //        $question->setName('Justin')->setSlug('comme un nom de fixtures')->setQuestion("Quel objet?");
    //        if(rand(1, 10) > 2) {
    //            $question->setAskedAt(new \DateTime(sprintf('%d days', rand(1, 108))));
    //        }
    //        $manager->persist($question);
    //        $manager->flush();
    //    }

    //  Avec foundry et faker
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        TagFactory::createMany(100);
        //Créer 10 questions, excuter symfony console doctrine:fixtures:load pour charger en db
        QuestionFactory::createMany(10);
        //Je passe les questions à une réponse
        AnswerFactory::createMany(50);
        //Les questions non publiées
        //QuestionFactory::new()->notPublished()->many(5)->create();
        AnswerFactory::new()->needsApproval()->many(20)->create();
        AnswerFactory::new()->spam()->many(10)->create();
    }
}
