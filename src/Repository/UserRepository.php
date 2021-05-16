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

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
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
        return $query->execute();
    }
    //find the user by his id
    public function findById(int $id):User
    {
        $command = $this->createQueryBuilder('p')
        ->where('p.id = :id')
        ->setParameter(':id',$id);

        $query = $command->getQuery();
        return $query->execute();
    }
    
}
