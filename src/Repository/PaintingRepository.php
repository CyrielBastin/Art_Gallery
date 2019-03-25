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
              LIMIT 7
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

    public function adminListPainting()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT id, image, title, price, discount, (ABS(discount - 100)/100)*price AS price_reduced
            FROM painting
            ORDER BY title
        ';

        $request = $conn->prepare($sql);
        $request->execute();

        return $request->fetchAll();
    }

    public function adminCommentsPaintingOverview()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT p.id, p.image, p.title, CONCAT(a.firstname, \' \', a.lastname) as artist, COUNT(pc.commentary) as number_of_comments
            FROM painting p
            LEFT JOIN painting_comment pc on p.id = pc.painting_id
            LEFT JOIN artist a on p.artist_id = a.id
            GROUP BY p.title
            ORDER BY p.title
        ';

        $request = $conn->prepare($sql);
        $request->execute();

        return $request->fetchAll();
    }

    public function adminPaintingCommentByUser($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT p.id, p.image, p.title
            FROM painting p
            LEFT JOIN painting_comment pc on p.id = pc.painting_id
            LEFT JOIN user u on pc.user_id = u.id
            WHERE u.id = :id
            GROUP BY p.title
            ORDER BY p.title
        ';

        $request = $conn->prepare($sql);
        $request->execute(['id' => $id]);

        return $request->fetchAll();
    }

    public function adminCommentsUserByPaintingId($user_id, $painting_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT u.id, pc.commentary, pc.posted_at, pc.id as painting_comment_id
            FROM user u
            LEFT JOIN painting_comment pc on u.id = pc.user_id
            LEFT JOIN painting p on pc.painting_id = p.id
            WHERE u.id = :user_id AND p.id = :painting_id
            ORDER BY pc.posted_at DESC
        ';

        $request = $conn->prepare($sql);
        $request->execute(['user_id' => $user_id, 'painting_id' => $painting_id]);

        return $request->fetchAll();
    }

}
