<?php

namespace App\DataFixtures;

use App\Entity\Organisation;
use App\Entity\Session;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $organisations = $manager->getRepository(Organisation::class)->findAll();
        foreach ($organisations as $organisation) {
            for ($i = 0; $i < $faker->numberBetween(1, 10); ++$i) {
                $session = (new Session())
                   ->setStart($faker->dateTimeBetween('-1 year', 'now'))
                    ->setOrganisation($organisation)
                ;
                $manager->persist($session);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            OrganisationFixtures::class
        ];
    }
}
