<?php

namespace App\Repository;

use App\Entity\PaintingDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaintingDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintingDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintingDiscount[]    findAll()
 * @method PaintingDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingDiscountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaintingDiscount::class);
    }

    // /**
    //  * @return PaintingDiscount[] Returns an array of PaintingDiscount objects
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
    public function findOneBySomeField($value): ?PaintingDiscount
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
