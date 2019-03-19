<?php

namespace App\Repository;

use App\Entity\PaintingComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaintingComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaintingComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaintingComment[]    findAll()
 * @method PaintingComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaintingCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaintingComment::class);
    }

    public function findByPaintingId($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT c.id, c.commentary, c.posted_at, up.avatar, up.pseudo, up.signature
            FROM painting_comment c
            INNER JOIN user u on c.user_id = u.id
            INNER JOIN user_profile up on u.id = up.user_id
            WHERE c.painting_id = :painting_id
            ORDER BY c.posted_at DESC
        ';

        try {
            $request = $conn->prepare($sql);
        } catch (DBALException $e) {}

        $request->execute(['painting_id' => $id]);

        return $request->fetchAll();
    }

}
