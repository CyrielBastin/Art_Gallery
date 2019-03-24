<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function adminListUser()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            SELECT u.id, up.avatar, u.email, up.pseudo, ur.name as role, u.roles_id
            FROM user u
            LEFT JOIN user_roles ur on u.roles_id = ur.id
            LEFT JOIN user_profile up on u.id = up.user_id
            ORDER BY u.roles_id, up.pseudo
        ';

        $request = $conn->prepare($sql);
        $request->execute();

        return $request->fetchAll();
    }

    public function adminSetRole($user_id, $role_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql='
            UPDATE user
            SET roles_id = :role_id
            WHERE id = :user_id
        ';

        $request = $conn->prepare($sql);
        $request->execute(['role_id' => $role_id, 'user_id' => $user_id]);
    }
}
