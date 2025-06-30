<?php

namespace App\DataFixtures;

use App\Factory\ProjectsFactory;
use App\Factory\StatusFactory;
use App\Factory\TagsFactory;
use App\Factory\TasksFactory;
use App\Factory\UsersFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        //StatusFactory::createMany(3);

        StatusFactory::createOne(['name' => 'To Do']);
        StatusFactory::createOne(['name' => 'Doing']);
        StatusFactory::createOne(['name' => 'Done']);

        TagsFactory::createMany(3);
        UsersFactory::createMany(10);
        ProjectsFactory::createMany(5);
        TasksFactory::createMany(15);

        $manager->flush();
    }
}
