<?php

namespace App\Repository;

use App\Entity\PaintingCommentDeleted;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaintingCommentDeleted|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintingCommentDeleted|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintingCommentDeleted[]    findAll()
 * @method PaintingCommentDeleted[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingCommentDeletedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaintingCommentDeleted::class);
    }

    // /**
    //  * @return PaintingCommentDeleted[] Returns an array of PaintingCommentDeleted objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaintingCommentDeleted
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
