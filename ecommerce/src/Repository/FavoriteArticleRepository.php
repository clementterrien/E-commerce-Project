<?php

namespace App\Repository;

use App\Entity\FavoriteArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FavoriteArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteArticle[]    findAll()
 * @method FavoriteArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteArticle::class);
    }

    // /**
    //  * @return FavoriteArticle[] Returns an array of FavoriteArticle objects
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
    public function findOneBySomeField($value): ?FavoriteArticle
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
