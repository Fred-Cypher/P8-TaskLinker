<?php

namespace App\Factory;

use App\Entity\Projects;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Projects>
 */
final class ProjectsFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Projects::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            //'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'createdAt' => new \DateTimeImmutable(),
            'name' => self::faker()->sentence(6),
            //'startingDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'startingDate' => self::faker()->dateTimeBetween('-6 months', 'now'),
            'status' => StatusFactory::random(),
            'deadline' => self::faker()->dateTimeBetween('now', '+6month'),
            'users' => UsersFactory::new()->many(2, 4),
            'tags' => TagsFactory::new()->many(1, 3),
            'isArchived' => self::faker()->boolean(20),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Projects $projects): void {})
        ;
    }
}
