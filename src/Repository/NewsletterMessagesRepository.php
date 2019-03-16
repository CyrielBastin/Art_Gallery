<?php

namespace App\Repository;

use App\Entity\NewsletterMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NewsletterMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterMessagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NewsletterMessages::class);
    }

    public function findAll()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT *
            FROM newsletter_messages
            ORDER BY created_at DESC
        ';

        $request = $conn->prepare($sql);
        $request->execute();

        return $request->fetchAll();
    }
}
