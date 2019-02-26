<?php

namespace App\Repository;

use App\Entity\PaintingMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaintingMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintingMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintingMedia[]    findAll()
 * @method PaintingMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingMediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaintingMedia::class);
    }

    // /**
    //  * @return PaintingMedia[] Returns an array of PaintingMedia objects
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
    public function findOneBySomeField($value): ?PaintingMedia
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
