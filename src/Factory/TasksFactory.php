<?php

namespace App\Factory;

use App\Entity\Tasks;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Tasks>
 */
final class TasksFactory extends PersistentProxyObjectFactory
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
        return Tasks::class;
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
            'project' => ProjectsFactory::random(),
            'status' => StatusFactory::random(),
            'title' => self::faker()->sentence(4),
            'description' => self::faker()->paragraph(2),
            'deadline' => self::faker()->dateTimeBetween('now', '+3 months'),
            'project' => ProjectsFactory::random(),
            'users'=> UsersFactory::new()->random(),
            'tags' => TagsFactory::new()->many(0, 2),
            'createdAt' => new \DateTimeImmutable(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Tasks $tasks): void {})
        ;
    }
}
