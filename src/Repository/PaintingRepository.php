<?php

namespace App\Repository;

use App\Entity\Painting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Painting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Painting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Painting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Painting::class);
    }


    public function findAll()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT p.id, p.image, p.title, pm.name AS media, dimensions, ps.name AS style,
            CONCAT(a.lastname, \' \', a.firstname) AS artist, year, price
            FROM painting p
              LEFT JOIN painting_media pm ON p.media_id = pm.id
              LEFT JOIN painting_style ps ON p.style_id = ps.id
              LEFT JOIN artist a ON p.artist_id = a.id
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute();

        return $request->fetchAll();
    }


}
