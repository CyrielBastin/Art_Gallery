<?php

namespace App\EventListener;


use App\Entity\UserMessages;
use App\Entity\UserMessagesDeleted;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserMessagesListener
{

    public function preRemove(UserMessages $userMessages, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        $message_delete = new UserMessagesDeleted();

        $message_delete
            ->setSenderId($userMessages->getSender()->getId())
            ->setReceiverId($userMessages->getReceiver()->getId())
            ->setMessage($userMessages->getMessage())
            ->setPostedAt($userMessages->getPostedAt())
            ;

        try {
            $em->persist($message_delete);
        } catch (ORMException $e) {}

        try {
            $em->flush();
        } catch (OptimisticLockException $e) {}
        catch (ORMException $e) {}
    }
}
