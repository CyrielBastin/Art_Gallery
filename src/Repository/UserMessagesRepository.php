<?php

namespace App\Repository;

use App\Entity\UserMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMessages[]    findAll()
 * @method UserMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMessagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserMessages::class);
    }

    public function findConversationsByUser($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT up.pseudo
            FROM user_messages
            LEFT JOIN user u ON user_messages.receiver_id = u.id
            LEFT JOIN user_profile up on u.id = up.user_id
            WHERE sender_id = :sender_id
            UNION
            SELECT p.pseudo
            FROM user_messages
            LEFT JOIN user u2 ON user_messages.sender_id = u2.id
            LEFT JOIN user_profile p on u2.id = p.user_id
            WHERE receiver_id = :receiver_id
            ORDER BY pseudo
        ';

        $request = $conn->prepare($sql);
        $request->execute(['sender_id' => $id, 'receiver_id' => $id]);

        return $request->fetchAll();
    }

    public function findMessagesPerConversation($user_id, $other_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT *
            FROM user_messages
            WHERE (sender_id = :user_id && receiver_id = :other_id) OR (sender_id = :other_id && receiver_id = :user_id)
            ORDER BY posted_at DESC
        ';

        $request = $conn->prepare($sql);
        $request->execute(['user_id' => $user_id, 'other_id' => $other_id]);

        return $request->fetchAll();
    }

}
