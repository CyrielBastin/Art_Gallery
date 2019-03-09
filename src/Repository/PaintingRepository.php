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
            CONCAT(a.lastname, \' \', a.firstname) AS artist, year, price, p.artist_id,
                   p.discount, (ABS(p.discount - 100)/100)*p.price AS price_reduced
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

    public function findById($id){
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT p.id, p.image, p.title, pm.name AS media, dimensions, ps.name AS style,
            CONCAT(a.lastname, \' \', a.firstname) AS artist, year, price, p.description, p.artist_id,
                   p.discount, (ABS(p.discount - 100)/100)*p.price AS price_reduced
            FROM painting p
              LEFT JOIN painting_media pm ON p.media_id = pm.id
              LEFT JOIN painting_style ps ON p.style_id = ps.id
              LEFT JOIN artist a ON p.artist_id = a.id
            WHERE p.id = :id
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute(['id' => $id]);

        return $request->fetch();
    }

    public function findLatest(){
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT p.id, p.image, p.title, pm.name AS media, dimensions, ps.name AS style,
            CONCAT(a.lastname, \' \', a.firstname) AS artist, year, price, p.artist_id,
                   p.discount, (ABS(p.discount - 100)/100)*p.price AS price_reduced
            FROM painting p
              LEFT JOIN painting_media pm ON p.media_id = pm.id
              LEFT JOIN painting_style ps ON p.style_id = ps.id
              LEFT JOIN artist a ON p.artist_id = a.id
              ORDER BY p.id DESC
              LIMIT 5
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute();

        return $request->fetchAll();
    }

    public function findByArtist($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT p.id, p.image, p.title, pm.name AS media, dimensions, ps.name AS style,
            CONCAT(a.lastname, \' \', a.firstname) AS artist, year, price,
                   p.discount, (ABS(p.discount - 100)/100)*p.price AS price_reduced
            FROM painting p
              LEFT JOIN painting_media pm ON p.media_id = pm.id
              LEFT JOIN painting_style ps ON p.style_id = ps.id
              LEFT JOIN artist a ON p.artist_id = a.id
            WHERE p.artist_id = :id
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute(['id' => $id]);

        return $request->fetchAll();
    }

    public function findByMedia($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT p.id, p.image, p.title, pm.name AS media, dimensions, ps.name AS style,
            CONCAT(a.lastname, \' \', a.firstname) AS artist, year, price, p.artist_id,
                   p.discount, (ABS(p.discount - 100)/100)*p.price AS price_reduced
            FROM painting p
              LEFT JOIN painting_media pm ON p.media_id = pm.id
              LEFT JOIN painting_style ps ON p.style_id = ps.id
              LEFT JOIN artist a ON p.artist_id = a.id
            WHERE p.media_id = :id
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute(['id' => $id]);

        return $request->fetchAll();
    }

    public function findByStyle($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT p.id, p.image, p.title, pm.name AS media, dimensions, ps.name AS style,
            CONCAT(a.lastname, \' \', a.firstname) AS artist, year, price, p.artist_id,
                   p.discount, (ABS(p.discount - 100)/100)*p.price AS price_reduced
            FROM painting p
              LEFT JOIN painting_media pm ON p.media_id = pm.id
              LEFT JOIN painting_style ps ON p.style_id = ps.id
              LEFT JOIN artist a ON p.artist_id = a.id
            WHERE p.style_id = :id
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute(['id' => $id]);

        return $request->fetchAll();
    }

    public function findDiscount()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT id, title, image, price, (ABS(discount - 100)/100)*price AS price_reduced
            FROM painting
            WHERE discount != 0
            ORDER BY price_reduced
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute();

        return $request->fetchAll();
    }

}
