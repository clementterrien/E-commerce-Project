<?php

namespace App\Repository;

use App\Entity\WineInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WineInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WineInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WineInventory[]    findAll()
 * @method WineInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WineInventory::class);
    }

    // /**
    //  * @return WineInventory[] Returns an array of WineInventory objects
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
    public function findOneBySomeField($value): ?WineInventory
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
