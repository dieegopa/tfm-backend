<?php

namespace App\Factory;

use App\Entity\Degree;
use App\Repository\DegreeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Degree>
 *
 * @method        Degree|Proxy create(array|callable $attributes = [])
 * @method static Degree|Proxy createOne(array $attributes = [])
 * @method static Degree|Proxy find(object|array|mixed $criteria)
 * @method static Degree|Proxy findOrCreate(array $attributes)
 * @method static Degree|Proxy first(string $sortedField = 'id')
 * @method static Degree|Proxy last(string $sortedField = 'id')
 * @method static Degree|Proxy random(array $attributes = [])
 * @method static Degree|Proxy randomOrCreate(array $attributes = [])
 * @method static DegreeRepository|RepositoryProxy repository()
 * @method static Degree[]|Proxy[] all()
 * @method static Degree[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Degree[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Degree[]|Proxy[] findBy(array $attributes)
 * @method static Degree[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Degree[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class DegreeFactory extends ModelFactory
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
            'name' => self::faker()->text(255),
            'school' => self::faker()->text(255),
            'slug' => self::faker()->text(255),
            'university' => UniversityFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Degree $degree): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Degree::class;
    }
}
