<?php

namespace App\DataFixtures;

use App\Entity\Organisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganisationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $organisation = (new Organisation())
            ->setName('Jeux de rôle')
            ->setCreatedAt(new \DateTime())
            ->setDescription('Organisation de parties de jeux de rôle')
        ;
        $manager->persist($organisation);
        $this->addReference('organisation', $organisation);

        for ($i = 0; $i < 10; ++$i) {
            $organisation = (new Organisation())
                ->setName($faker->name)
                ->setCreatedAt(new \DateTime())
                ->setDescription($faker->sentence(50))
            ;
            $manager->persist($organisation);
        }

        $manager->flush();
    }
}
