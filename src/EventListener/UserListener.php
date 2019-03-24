<?php

namespace App\EventListener;


use App\Entity\User;
use App\Entity\UserDeleted;
use App\Entity\UserProfile;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserListener
{

    public function postPersist(User $user, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        $conn = $em->getConnection();
        $sql='
            UPDATE user
            SET roles_id = 6
            WHERE id = :id
        ';
        $request = $conn->prepare($sql);
        $request->execute(['id' => $user->getId()]);

        $user_profile = new UserProfile();
        $array = explode('@', $user->getEmail());
        $pseudo = $array[0].$user->getId();

        $user_profile->setUser($user)
                    ->setAvatar('avatar_default.png')
                    ->setPseudo($pseudo)
                    ->setUpdatedAt(new \DateTime('now'))
        ;

        $em->persist($user_profile);

        $em->flush();
    }

    public function preRemove(User $user, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        $user_deleted = new UserDeleted();

        $user_deleted->setEmail($user->getEmail())
                    ->setPseudo($user->getUserProfile()->getPseudo())
                    ->setSignature($user->getUserProfile()->getSignature())
                    ->setLastname($user->getUserProfile()->getLastname())
                    ->setFirstname($user->getUserProfile()->getFirstname())
                    ->setDateOfBirth($user->getUserProfile()->getDateOfBirth())
                    ->setCountry($user->getUserProfile()->getCountry())
                    ->setLikes($user->getUserProfile()->getLikes())
                    ->setDislikes($user->getUserProfile()->getDislikes())
        ;

        $em->persist($user_deleted);

        $em->flush();
    }
}
