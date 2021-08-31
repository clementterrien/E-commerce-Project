<?php

namespace App\Repository;

use App\Entity\WineCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WineCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WineCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WineCategory[]    findAll()
 * @method WineCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WineCategory::class);
    }

    // /**
    //  * @return WineCategory[] Returns an array of WineCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WineCategory
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
