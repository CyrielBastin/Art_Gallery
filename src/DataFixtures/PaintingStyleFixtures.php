<?php

namespace App\DataFixtures;

use App\Entity\PaintingStyle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class PaintingStyleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $styles = ['Baroque', 'Modernism', 'Impressionism', 'Abstract styles', 'Photorealism', 'Surrealism', 'Ink and Wash painting', 'Yamato-e'];
        $faker = Faker\Factory::create();

        foreach ($styles as $style){
            $painting_style = new PaintingStyle();

            $painting_style->setName($style)
                ->setDescription($faker->text(300));

            $manager->persist($painting_style);
        }

        $manager->flush();
    }
}
