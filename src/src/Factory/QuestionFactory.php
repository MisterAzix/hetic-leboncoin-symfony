<?php

namespace App\Factory;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Question>
 *
 * @method static Question|Proxy createOne(array $attributes = [])
 * @method static Question[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Question[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Question|Proxy find(object|array|mixed $criteria)
 * @method static Question|Proxy findOrCreate(array $attributes)
 * @method static Question|Proxy first(string $sortedField = 'id')
 * @method static Question|Proxy last(string $sortedField = 'id')
 * @method static Question|Proxy random(array $attributes = [])
 * @method static Question|Proxy randomOrCreate(array $attributes = [])
 * @method static Question[]|Proxy[] all()
 * @method static Question[]|Proxy[] findBy(array $attributes)
 * @method static Question[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Question[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static QuestionRepository|RepositoryProxy repository()
 * @method Question|Proxy create(array|callable $attributes = [])
 */
final class QuestionFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

//    Contrôler les fixtures
//    Changer la valeur d'un attribut
public function notPublished(): self {
        return $this->addState(['askedAt' => null]);
}


//  Avec faker
//  Générer plus ou moins n’importe quoi comme données, des dates, des, des faux noms, n’importe quoi.
    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => self::faker()->realText(40),
            'slug' => self::faker()->slug(),
            'question' => self::faker()->paragraph(rand(1, 4), true),
            'askedAt' => self::faker()->dateTimeBetween('-100 days', '-1 second'),
            'createdAt' => self::faker()->dateTimeBetween('-1 year'), // TODO add DATETIME ORM type manually
            'updatedAt' => self::faker()->dateTimeBetween('-2 year'), // TODO add DATETIME ORM type manually
            'user' => UserFactory::random(),
        ];
    }

//    Sans faker
//    retourner un array utiles pour créer un objet « Question
//    protected function getDefaults(): array
//    {
//        return [
//            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
//            'name' => 'pizza'.rand(0, 1000),
//            'slug' => 'pizza du coin'.rand(0, 1000),
//            'question' => 'Ma pizza est elle prête ?'.rand(0, 1000),
//            'askedAt' => rand(1, 50) > 2 ? new \DateTime(sprintf('%d days', rand(1, 108))): null
//        ];
//    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Question $question): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Question::class;
    }
}
