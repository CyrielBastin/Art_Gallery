<?php

namespace App\EventListener;


use App\Entity\Painting;
use App\Entity\PaintingDelete;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class PaintingListener
{
    public function preRemove(Painting $painting, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        $painting_delete = new PaintingDelete();

        $painting_delete->setId($painting->getId())
                        ->setArtistId($painting->getArtist()->getId())
                        ->setMediaId($painting->getMedia()->getId())
                        ->setStyleId($painting->getStyle()->getId())
                        ->setTitle($painting->getTitle())
                        ->setDimensions($painting->getDimensions())
                        ->setYear($painting->getYear())
                        ->setDescription($painting->getDescription())
                        ->setPrice($painting->getPrice())
                        ;

        try {
            $em->persist($painting_delete);
        } catch (ORMException $e) {}

        try {
            $em->flush();
        } catch (OptimisticLockException $e) {}
          catch (ORMException $e) {}
    }

}
