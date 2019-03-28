<?php

namespace App\EventListener;


use App\Entity\PaintingComment;
use App\Entity\PaintingCommentDeleted;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class PaintingCommentListener
{
    public function preRemove(PaintingComment $paintingComment, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $comment_deleted = new PaintingCommentDeleted();

        $comment_deleted
            ->setUserId($paintingComment->getUser()->getId())
            ->setPaintingId($paintingComment->getPainting()->getId())
            ->setCommentary($paintingComment->getCommentary())
            ->setPostedAt($paintingComment->getPostedAt())
            ;

        try {
            $em->persist($comment_deleted);
        } catch (ORMException $e) {}

        try {
            $em->flush();
        } catch (OptimisticLockException $e) {}
         catch (ORMException $e) {}
    }
}
