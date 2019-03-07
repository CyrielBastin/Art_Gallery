<?php

namespace App\Repository;

use App\Entity\PaintingStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Faker;

/**
 * @method PaintingStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintingStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintingStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingStyleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaintingStyle::class);
    }

    public function findAll()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT *
            FROM painting_style
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute();

        return $request->fetchAll();
    }

    public function findById($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT *
            FROM painting_style
            WHERE id = :id
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute(['id' => $id]);

        return $request->fetch();
    }

}
