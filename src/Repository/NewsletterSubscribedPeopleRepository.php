<?php

namespace App\Repository;

use App\Entity\NewsletterSubscribedPeople;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NewsletterSubscribedPeople|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterSubscribedPeople|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterSubscribedPeople[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterSubscribedPeopleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NewsletterSubscribedPeople::class);
    }

    public function findAll()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT email
            FROM newsletter_subscribed_people
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute();

        return $request->fetchAll();
    }
}
