<?php

namespace App\DataFixtures;

use App\Entity\Painting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ZPaintingFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $dimensions = ['30 x 40', '50 x 50', '80 x 60', '120 x 90', '70 x 50', '40 x 70', '150 x 180', '90 x 140'];

        $faker = Faker\Factory::create();

        for($i = 1; $i <= 109; $i++){
            $painting = new Painting();

            $painting->setImage($i.'.jpg')
                ->setTitle($faker->text(20))
                ->setDimensions($dimensions[$faker->numberBetween(0, 7)])
                ->setYear($faker->dateTime('now'))
                ->setDescripition($faker->text(200))
                ->setPrice($faker->numberBetween(70, 500));

            $manager->persist($painting);
        }

        $manager->flush();
    }
}
