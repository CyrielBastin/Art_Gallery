<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Artist::class);
    }


    public function findAll()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT id, image, CONCAT(lastname, \' \', firstname) AS full_name
            FROM artist
            ORDER BY lastname
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
            SELECT *, CONCAT(lastname, \' \', firstname) AS full_name
            FROM artist
            WHERE id = :id
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute(['id' => $id]);

        return $request->fetch();
    }
}
