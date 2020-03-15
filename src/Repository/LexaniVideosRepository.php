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

    /**
    * @return LexaniVideos[]
    */

    public function findNewVideoData()
    {
        return $this->findBy(['parseType' => 'new']);
    }

    public function findOldVideoData()
    {
        return $this->findBy(['parseType' => 'old']);
    }





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
