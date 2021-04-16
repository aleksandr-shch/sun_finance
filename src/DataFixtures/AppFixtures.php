<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (range(1, 1000) as $item) {
            $client = new Client();
            $client->setFirstName($faker->firstName());
            $client->setLastName($faker->lastName());
            $client->setEmail($faker->email());
            $client->setPhoneNumber($faker->e164PhoneNumber());

            $application = new Application();
            $application->setClientId($client);
            $application->setTerm($faker->numberBetween(10, 30));
            $application->setAmount($faker->randomFloat(2, 100, 5000));
            $application->setCurrency($faker->currencyCode());
            $client->getApplications()->add($application);

            $application = new Application();
            $application->setClientId($client);
            $application->setTerm($faker->numberBetween(10, 30));
            $application->setAmount($faker->randomFloat(2, 100, 5000));
            $application->setCurrency($faker->currencyCode());
            $client->getApplications()->add($application);

            $manager->persist($client);
        }

        $manager->flush();
    }
}
