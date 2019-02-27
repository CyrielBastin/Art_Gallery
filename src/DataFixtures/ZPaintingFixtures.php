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
        $dimensions = ['300 x 400', '500 x 500', '800 x 600', '1200 x 900', '700 x 500', '400 x 700', '1500 x 1800', '900 x 1400'];

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
