<?php

namespace App\Factory;

use App\Entity\Ad;
use App\Repository\AdRepository;
use App\Service\UploadHelper;
use Symfony\Component\HttpFoundation\File\File;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Ad>
 *
 * @method static Ad|Proxy createOne(array $attributes = [])
 * @method static Ad[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Ad[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Ad|Proxy find(object|array|mixed $criteria)
 * @method static Ad|Proxy findOrCreate(array $attributes)
 * @method static Ad|Proxy first(string $sortedField = 'id')
 * @method static Ad|Proxy last(string $sortedField = 'id')
 * @method static Ad|Proxy random(array $attributes = [])
 * @method static Ad|Proxy randomOrCreate(array $attributes = [])
 * @method static Ad[]|Proxy[] all()
 * @method static Ad[]|Proxy[] findBy(array $attributes)
 * @method static Ad[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Ad[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AdRepository|RepositoryProxy repository()
 * @method Ad|Proxy create(array|callable $attributes = [])
 */
final class AdFactory extends ModelFactory
{
    private $helper;
    private static $templateThumbnails = [
        'blue_product.png',
        'dark_blue_product.png',
        'green_product.png',
        'orange_product.png',
        'pink_product.png',
        'purple_product.png',
        'red_product.png',
        'yellow_product.png',
    ];

    public function __construct(UploadHelper $helper)
    {
        parent::__construct();
        $this->helper = $helper;
    }

    protected function getDefaults(): array
    {
        $thumbnailsNumber = rand(1, 3);
        $thumbnailsArray = [];
        for ($i = 0; $i < $thumbnailsNumber; $i++) {
            $thumbnailsArray[] = $this->helper->fixtureUpload(
                new File(__DIR__ . '/Template_images/' . self::faker()->randomElement(self::$templateThumbnails))
            );
        }

        return [
            'title' => self::faker()->text(40),
            'description' => self::faker()->realText(),
            'price' => self::faker()->randomFloat(),
            'user' => UserFactory::random(),
            'tags' => TagFactory::randomRange(0, 5),
            'thumbnails_urls' => $thumbnailsArray,
            'created_at' => self::faker()->dateTime('now')
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            # ->afterInstantiate(function(Ad $ad): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Ad::class;
    }
}
