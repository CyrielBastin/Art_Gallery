<?php

namespace App\Repository;

use App\Entity\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfile[]    findAll()
 * @method UserProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserProfile::class);
    }

    public function findOneByPseudo($pseudo)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT u.id, u.email, ur.name as role, up.avatar, up.pseudo, up.firstname, up.lastname,
                   up.country, up.date_of_birth, up.likes, up.dislikes, up.signature
            FROM user_profile up
            LEFT JOIN user u on up.user_id = u.id
            LEFT JOIN user_roles ur on u.roles_id = ur.id
            WHERE up.pseudo = :pseudo
        ';

        $request = $conn->prepare($sql);
        $request->execute(['pseudo' => $pseudo]);

        return $request->fetch();
    }
}
