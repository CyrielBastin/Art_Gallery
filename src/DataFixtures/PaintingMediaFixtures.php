<?php

namespace App\DataFixtures;

use App\Entity\PaintingMedia;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class PaintingMediaFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $medias = ['Oil', 'Pastel', 'Acrylic', 'Watercolor', 'Ink', 'Encaustic', 'Fresco', 'Gouache', 'Enamel', 'Spray paint', 'Tempera'];
        $faker = Faker\Factory::create();

        foreach ($medias as $media){
            $painting_media = new PaintingMedia();

            $painting_media->setName($media)
                ->setDescription($faker->text(300));

            $manager->persist($painting_media);
        }

        $manager->flush();
    }
}
