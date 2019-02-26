<?php

namespace App\Repository;

use App\Entity\PaintingStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaintingStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintingStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintingStyle[]    findAll()
 * @method PaintingStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingStyleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaintingStyle::class);
    }

    // /**
    //  * @return PaintingStyle[] Returns an array of PaintingStyle objects
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
    public function findOneBySomeField($value): ?PaintingStyle
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
