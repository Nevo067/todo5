<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    //find the user by his id and password
    public function findUserByLoginAndPassword(string $login,string $password)
    {
        //Command sql
        $command = $this->createQueryBuilder('p')
        ->andWhere('p.password LIKE :password')
        ->andWhere('p.login LIKE :login')
        ->setParameter(':login',$login)
        ->setParameter(':password',$password);

        $query = $command->getQuery();
        //execute the sql command
        return $query->getSingleResult();
    }
    //find the user by his id
    public function findById(int $id):User
    {
        $command = $this->createQueryBuilder('p')
        ->where('p.id = :id')
        ->setParameter(':id',$id);

        $query = $command->getQuery();
        return $query->execute()[0];
    }
    
}
