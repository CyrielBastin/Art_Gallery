<?php

namespace App\Repository;

use App\Entity\UserMessagesDeleted;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserMessagesDeleted|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMessagesDeleted|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMessagesDeleted[]    findAll()
 * @method UserMessagesDeleted[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMessagesDeletedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserMessagesDeleted::class);
    }

    // /**
    //  * @return UserMessagesDeleted[] Returns an array of UserMessagesDeleted objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserMessagesDeleted
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
