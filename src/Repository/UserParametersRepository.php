<?php

namespace App\Repository;

use App\Entity\UserParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserParameters|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserParameters|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserParameters[]    findAll()
 * @method UserParameters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserParametersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserParameters::class);
    }

    // /**
    //  * @return UserParameters[] Returns an array of UserParameters objects
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
    public function findOneBySomeField($value): ?UserParameters
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
