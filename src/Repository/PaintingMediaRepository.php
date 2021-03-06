<?php

namespace App\Repository;

use App\Entity\PaintingMedia;
use Doctrine\DBAL\DBALException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Faker;

/**
 * @method PaintingMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintingMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintingMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingMediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaintingMedia::class);
    }

    public function findAll()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT *
            FROM painting_media
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
            FROM painting_media
            WHERE id = :id
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute(['id' => $id]);

        return $request->fetch();
    }

    public function adminListMedia()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT id, image, name
            FROM painting_media
            ORDER BY name
        ';

        $request = $conn->prepare($sql);
        $request->execute();

        return $request->fetchAll();
    }

}
