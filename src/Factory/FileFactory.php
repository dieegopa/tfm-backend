<?php

namespace App\Factory;

use App\Entity\File;
use App\Repository\FileRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<File>
 *
 * @method        File|Proxy create(array|callable $attributes = [])
 * @method static File|Proxy createOne(array $attributes = [])
 * @method static File|Proxy find(object|array|mixed $criteria)
 * @method static File|Proxy findOrCreate(array $attributes)
 * @method static File|Proxy first(string $sortedField = 'id')
 * @method static File|Proxy last(string $sortedField = 'id')
 * @method static File|Proxy random(array $attributes = [])
 * @method static File|Proxy randomOrCreate(array $attributes = [])
 * @method static FileRepository|RepositoryProxy repository()
 * @method static File[]|Proxy[] all()
 * @method static File[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static File[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static File[]|Proxy[] findBy(array $attributes)
 * @method static File[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static File[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class FileFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'category' => self::faker()->text(255),
            'extra' => self::faker()->text(255),
            'name' => self::faker()->text(255),
            'subject' => SubjectFactory::new(),
            'type' => self::faker()->text(255),
            'uniqueName' => self::faker()->text(255),
            'url' => self::faker()->text(255),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(File $file): void {})
        ;
    }

    protected static function getClass(): string
    {
        return File::class;
    }
}
