<?php

namespace App\Repository;

use App\Entity\LexaniVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LexaniVideos|null find($id, $lockMode = null, $lockVersion = null)
 * @method LexaniVideos|null findOneBy(array $criteria, array $orderBy = null)
 * @method LexaniVideos[]    findAll()
 * @method LexaniVideos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LexaniVideosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LexaniVideos::class);
    }

    public function findNewVideoData()
    {
        return $this->findBy(['parseType' => 'new']);
    }

    public function findOldVideoData()
    {
        return $this->findBy(['parseType' => 'old']);
    }

    // /**
    //  * @return LexaniVideos[] Returns an array of LexaniVideos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LexaniVideos
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
