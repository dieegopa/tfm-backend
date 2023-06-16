<?php

namespace App\Factory;

use App\Entity\Staff;
use App\Repository\StaffRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Staff>
 *
 * @method        Staff|Proxy create(array|callable $attributes = [])
 * @method static Staff|Proxy createOne(array $attributes = [])
 * @method static Staff|Proxy find(object|array|mixed $criteria)
 * @method static Staff|Proxy findOrCreate(array $attributes)
 * @method static Staff|Proxy first(string $sortedField = 'id')
 * @method static Staff|Proxy last(string $sortedField = 'id')
 * @method static Staff|Proxy random(array $attributes = [])
 * @method static Staff|Proxy randomOrCreate(array $attributes = [])
 * @method static StaffRepository|RepositoryProxy repository()
 * @method static Staff[]|Proxy[] all()
 * @method static Staff[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Staff[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Staff[]|Proxy[] findBy(array $attributes)
 * @method static Staff[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Staff[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class StaffFactory extends ModelFactory
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
            'email' => self::faker()->text(180),
            'password' => self::faker()->text(),
            'roles' => [],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Staff $staff): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Staff::class;
    }
}
