<?php

namespace App\Repository;

use App\Entity\PaintingDelete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaintingDelete|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintingDelete|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintingDelete[]    findAll()
 * @method PaintingDelete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingDeleteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaintingDelete::class);
    }

    // /**
    //  * @return PaintingDelete[] Returns an array of PaintingDelete objects
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
    public function findOneBySomeField($value): ?PaintingDelete
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
