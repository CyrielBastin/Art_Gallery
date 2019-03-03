<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ArtistFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        for($i = 1; $i <= 20; $i++){
            $artist = new Artist();
            $artist->setImage('artist_'.$i)
                ->setLastname($faker->lastName)
                ->setFirstname($faker->firstName('male'|'female'))
                ->setCountry($faker->country)
                ->setDateOfBirth($faker->dateTimeBetween('-1000 years', 'now'))
                ->setCommentary($faker->text(1200));

            $manager->persist($artist);
        }

        $manager->flush();
    }
}
