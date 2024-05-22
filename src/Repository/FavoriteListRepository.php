<?php

namespace App\Repository;

use App\Entity\FavoriteList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FavoriteList|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteList|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteList[]    findAll()
 * @method FavoriteList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteList::class);
    }

    // /**
    //  * @return FavoriteList[] Returns an array of FavoriteList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FavoriteList
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
