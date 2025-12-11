<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{

    
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::USER_REFERENCE, User::class);
        // Tâche créée il y a 10 jours
        $task1 = new Task();
        $task1->setName('Tâche ancienne');
        $task1->setDescription('Cette tâche a été créée il y a plus de 7 jours');
        $task1->setCreatedAt(new \DateTimeImmutable('-10 days'));
        $task1->setUpdatedAt(new \DateTimeImmutable('-10 days'));
        $task1->setAuthor($user);

        $manager->persist($task1);

        // Tâche récente (créée aujourd'hui)
        $task2 = new Task();
        $task2->setName('Tâche récente');
        $task2->setDescription('Cette tâche a été créée aujourd\'hui');
        $task2->setCreatedAt(new \DateTimeImmutable());
        $task2->setUpdatedAt(new \DateTimeImmutable());
        $task2->setAuthor($user);

        $manager->persist($task2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
