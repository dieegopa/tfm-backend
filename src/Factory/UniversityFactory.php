<?php

namespace App\Factory;

use App\Entity\University;
use App\Repository\UniversityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<University>
 *
 * @method        University|Proxy create(array|callable $attributes = [])
 * @method static University|Proxy createOne(array $attributes = [])
 * @method static University|Proxy find(object|array|mixed $criteria)
 * @method static University|Proxy findOrCreate(array $attributes)
 * @method static University|Proxy first(string $sortedField = 'id')
 * @method static University|Proxy last(string $sortedField = 'id')
 * @method static University|Proxy random(array $attributes = [])
 * @method static University|Proxy randomOrCreate(array $attributes = [])
 * @method static UniversityRepository|RepositoryProxy repository()
 * @method static University[]|Proxy[] all()
 * @method static University[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static University[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static University[]|Proxy[] findBy(array $attributes)
 * @method static University[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static University[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UniversityFactory extends ModelFactory
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
            'image_url' => self::faker()->text(255),
            'name' => self::faker()->text(255),
            'slug' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(University $university): void {})
        ;
    }

    protected static function getClass(): string
    {
        return University::class;
    }
}
